<?php
namespace App\Services\Order;
use App\Services\BaseService;
use App\Models\OrderDetail;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderDetailsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Cart\CartRepository;
use App\Jobs\SendOrderMail;
use App\Jobs\SendTelegramNotification;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Models\Order;


class OrderService extends BaseService
{

    protected $orderRepository;
    protected $orderDetailsRepository;
    protected $cartRepository;
    protected $productRepository;
    protected $productVariantRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderDetailsRepository $orderDetailsRepository,
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderDetailsRepository = $orderDetailsRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['created_at', 'code', 'total'],
            ],
            'condition' => [
                'status' => $request->input('publish') == 0 ? 0 : $request->input('publish'),
                'deleted_at' => null,
            ],
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : ['id', 'asc'],
            'perpage' => (int) (isset($request['perpage']) && $request['perpage'] != 0 ? $request['perpage'] : 10),
        ];
    }

    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $users = $this->orderRepository->pagination($agruments);
        return $users;
    }
    private function paginateAgrumentClient($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['created_at', 'code', 'total'],
            ],
            'condition' => [
                'status' => $request->input('publish') == 0 ? 0 : $request->input('publish'),
                'user_id' => Auth::id(),
            ],
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : ['id', 'asc'],
            'perpage' => (int) (isset($request['perpage']) && $request['perpage'] != 0 ? $request['perpage'] : 10),
        ];
    }

    public function paginateClient($request)
    {
        $agruments = $this->paginateAgrumentClient($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $users = $this->orderRepository->pagination($agruments);
        return $users;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $storeOrder = $this->storeOrder($request);
            $storeOrderDetail = $this->storeOrderDetail($request, $storeOrder);
            $this->cartRepository->deleteCart(auth()->id());
            SendOrderMail::dispatch($storeOrder);
            $message = "🛍️ *Đơn hàng mới đã được tạo!*\n\n"
                . "📦 *Thông tin đơn hàng:*\n"
                . "🆔 *Mã đơn hàng:* {$storeOrder->code}\n"
                . "👤 *Khách hàng:* {$storeOrder->user->name}\n"
                . "💰 *Tổng tiền:* " . number_format($storeOrder->total) . " VND\n\n"
                . "⏰ *Thời gian đặt:* " . now()->format('H:i:s d/m/Y') . "\n"
                . "🔗 *Chi tiết đơn hàng:* [Xem tại đây](" . route('order.show', $storeOrder->id) . ")\n";
            SendTelegramNotification::dispatch($message);
            DB::commit();
            return $storeOrder;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            // $this->log($e);
            // return false;
        }
    }

    private function storeOrder($request)
    {
        // Validate bắt buộc nếu không dùng form request validator
        $requiredFields = ['province_id', 'district_id', 'ward_id'];
        foreach ($requiredFields as $field) {
            if (!$request->filled($field)) {
                throw new \Exception("Trường '$field' bắt buộc.");
            }
        }
        
        $payload = $request->only([
            'name', 'phone', 'email', 'province_id', 'district_id', 'ward_id',
            'address', 'note', 'status', 'payment_status', 'payment_method_id',
            'fee_ship', 'cart', 'payment_method'
        ]);

        // ✅ Logic bom hàng mới - kiểm tra giao hàng thất bại
        $phone = $request->input('phone');
        $totalDeliveryFailed = \App\Models\Order::where('phone', $phone)
            ->where('delivery_failed_count', '>', 0)
            ->sum('delivery_failed_count');
            
        $payload['is_bom'] = $totalDeliveryFailed >= 2 ? 1 : 0;
        $payload['delivery_failed_count'] = 0; // Đơn mới bắt đầu từ 0
        $payload['last_delivery_failed_at'] = null;
        
        $payload['total'] = $request->input('total', 0);

        if ($payload['total'] == 0 && !empty($payload['cart'])) {
            $cartData = is_string($payload['cart']) ? json_decode($payload['cart'], true) : $payload['cart'];
            $calculatedTotal = 0;

            foreach ($cartData as $item) {
                $calculatedTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
              }

            $payload['total'] = $calculatedTotal;
        }

        $payload['code'] = orderCode();
        $payload['user_id'] = auth()->id();

        return $this->orderRepository->create($payload);
    }

    private function storeOrderDetail($request, $storeOrder)
    {
        $payload = $request->only('quantity', 'sku', 'product_id', 'name_orderDetail', 'price');
        $result = [];
        $updateQuantity = [];
        foreach ($payload['sku'] as $key => $value) {
            $result[] = [
                'order_id' => $storeOrder->id,
                'product_id' => (int) $payload['product_id'][$key],
                'sku' => $value,
                'name' => $payload['name_orderDetail'][$key],
                'quantity' => (int) $payload['quantity'][$key],
                'price' => (float) $payload['price'][$key],
            ];
            $updateQuantity[] = [
                'product_id' => (int) $payload['product_id'][$key],
                'quantity' => (int) $payload['quantity'][$key],
            ];
        }

        $check = $this->orderDetailsRepository->insert($result);
        // lây ra sku và số lượng sản phẩm để cập nhật lại số lượng sản phẩm
        $this->updateQuantityProduct($updateQuantity);
        return $check;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method', 'quantity']);
            $updateOrder = $this->updateOrder($request, (int) $id);
            $storeOrderDetail = $this->UpdateOrderDetail($request, $id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            // $this->log($e);
            // return false;
        }
    }

    private function updateOrder($request, $id)
    {
        $payload = $request->only([
            'name', 'phone', 'email', 'province_id', 'district_id',
            'ward_id', 'address', 'note', 'status', 'payment_status',
            'payment_method_id', 'total', 'cart', 'fee_ship'
        ]);

        // ✅ Logic bom hàng cải tiến
        $phone = $request->phone;
        $status = $request->status;
        
        // Log để debug
        \Log::info('Updating order with bom logic:', [
            'order_id' => $id,
            'phone' => $phone,
            'status' => $status
        ]);
        
        // Lấy đơn hàng hiện tại để so sánh trạng thái
        $currentOrder = $this->orderRepository->findById($id);
        
        // Logic xử lý thay đổi trạng thái và delivery_failed_count
        
        // Nếu trạng thái thay đổi từ delivery_failed sang trạng thái khác, giảm số lần thất bại
        if ($currentOrder && $currentOrder->status === 'delivery_failed' && $status !== 'delivery_failed') {
            $newFailedCount = max(0, $currentOrder->delivery_failed_count - 1);
            $payload['delivery_failed_count'] = $newFailedCount;
            
            // Nếu không còn lần thất bại nào, xóa timestamp
            if ($newFailedCount == 0) {
                $payload['last_delivery_failed_at'] = null;
            }
            
            \Log::info('Order status changed from delivery_failed (decreasing failed count):', [
                'order_id' => $id,
                'phone' => $phone,
                'old_status' => $currentOrder->status,
                'new_status' => $status,
                'old_failed_count' => $currentOrder->delivery_failed_count,
                'new_failed_count' => $newFailedCount
            ]);
        }
        // Nếu trạng thái thay đổi từ trạng thái khác về delivery_failed, tăng số lần thất bại
        elseif ($status === 'delivery_failed' && $currentOrder && $currentOrder->status !== 'delivery_failed') {
            $newFailedCount = $currentOrder->delivery_failed_count + 1;
            $payload['delivery_failed_count'] = $newFailedCount;
            $payload['last_delivery_failed_at'] = now();
            
            \Log::info('Order status changed to delivery_failed (increasing failed count):', [
                'order_id' => $id,
                'phone' => $phone,
                'old_status' => $currentOrder->status,
                'new_status' => $status,
                'old_failed_count' => $currentOrder->delivery_failed_count,
                'new_failed_count' => $newFailedCount
            ]);
        }
        
        // Cập nhật đơn hàng trước
        $result = $this->orderRepository->update($id, $payload);
        
        // Sau đó kiểm tra và cập nhật bom hàng
        $this->updateBomHangStatus($phone);

        \Log::info('Order updated successfully:', [
            'order_id' => $id,
            'phone' => $phone,
            'status' => $status,
            'delivery_failed_count' => $payload['delivery_failed_count'] ?? $currentOrder->delivery_failed_count ?? 0
        ]);

        return $result;
    }

    /**
     * ✅ Cập nhật trạng thái bom hàng cho số điện thoại - Logic mới
     */
    private function updateBomHangStatus($phone)
    {
        if (!$phone) {
            \Log::warning('updateBomHangStatus called with empty phone');
            return;
        }
        
        // Đếm tổng số lần giao hàng thất bại
        $totalDeliveryFailed = Order::where('phone', $phone)
            ->where('delivery_failed_count', '>', 0)
            ->sum('delivery_failed_count');
            
        \Log::info('Checking bom hang status (new logic):', [
            'phone' => $phone,
            'total_delivery_failed' => $totalDeliveryFailed
        ]);
        
        // ✅ Nếu >= 2 lần giao hàng thất bại, đánh dấu bom hàng
        if ($totalDeliveryFailed >= 2) {
            $updatedCount = Order::where('phone', $phone)->update(['is_bom' => 1]);
            
            \Log::info('Updated bom orders (bom hang activated):', [
                'phone' => $phone,
                'updated_count' => $updatedCount,
                'total_delivery_failed' => $totalDeliveryFailed
            ]);
        } else {
            // Nếu < 2 lần thất bại, chưa bom hàng
            $updatedCount = Order::where('phone', $phone)->update(['is_bom' => 0]);
            
            \Log::info('Not bom hang yet:', [
                'phone' => $phone,
                'updated_count' => $updatedCount,
                'total_delivery_failed' => $totalDeliveryFailed
            ]);
        }
    }

    /**
     * ✅ THÊM MỚI: Kiểm tra trạng thái bom hàng cho debugging
     */
    public function checkBomHangStatus($phone)
    {
        $orders = Order::where('phone', $phone)->get();
        $totalFailed = $orders->sum('delivery_failed_count');
        $bomOrders = $orders->where('is_bom', 1);
        
        $debugInfo = [
            'phone' => $phone,
            'total_orders' => $orders->count(),
            'total_delivery_failed' => $totalFailed,
            'bom_orders_count' => $bomOrders->count(),
            'orders_detail' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'delivery_failed_count' => $order->delivery_failed_count,
                    'is_bom' => $order->is_bom,
                    'last_delivery_failed_at' => $order->last_delivery_failed_at
                ];
            })->toArray()
        ];
        
        \Log::info('Bom hang debug info:', $debugInfo);
        return $debugInfo;
    }

    /**
     * ✅ THÊM MỚI: Force update bom hàng cho tất cả đơn hàng
     */
    public function forceUpdateAllBomHang()
    {
        DB::beginTransaction();
        try {
            // Lấy tất cả số điện thoại có đơn hàng
            $phones = Order::distinct()->pluck('phone')->filter();
            
            $updatedCount = 0;
            foreach ($phones as $phone) {
                $this->updateBomHangStatus($phone);
                $updatedCount++;
            }
            
            \Log::info('Force updated bom hang for all phones:', [
                'total_phones' => $updatedCount
            ]);
            
            DB::commit();
            return $updatedCount;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error force updating bom hang:', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * ✅ THÊM MỚI: Sửa lại delivery_failed_count cho các đơn hàng có trạng thái delivery_failed
     */
    public function fixDeliveryFailedCount()
    {
        DB::beginTransaction();
        try {
            // Tìm tất cả đơn hàng có trạng thái delivery_failed nhưng delivery_failed_count = 0
            $ordersToFix = Order::where('status', 'delivery_failed')
                ->where('delivery_failed_count', 0)
                ->get();
            
            $fixedCount = 0;
            foreach ($ordersToFix as $order) {
                // Cập nhật delivery_failed_count = 1 cho các đơn hàng này
                $this->orderRepository->update($order->id, [
                    'delivery_failed_count' => 1,
                    'last_delivery_failed_at' => $order->updated_at ?? now()
                ]);
                $fixedCount++;
                
                \Log::info('Fixed delivery_failed_count for order:', [
                    'order_id' => $order->id,
                    'phone' => $order->phone,
                    'status' => $order->status
                ]);
            }
            
            // Sau khi sửa, cập nhật lại bom hàng cho tất cả số điện thoại
            $phones = Order::distinct()->pluck('phone')->filter();
            foreach ($phones as $phone) {
                $this->updateBomHangStatus($phone);
            }
            
            \Log::info('Fixed delivery_failed_count for orders:', [
                'fixed_count' => $fixedCount,
                'total_phones' => $phones->count()
            ]);
            
            DB::commit();
            return $fixedCount;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error fixing delivery_failed_count:', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * ✅ THÊM MỚI: Đánh dấu giao hàng thất bại
     */
    public function markDeliveryFailed($orderId, $reason = null)
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->findById($orderId);
            
            if (!$order) {
                throw new \Exception('Không tìm thấy đơn hàng');
            }
            
            // Tăng số lần giao hàng thất bại
            $newFailedCount = $order->delivery_failed_count + 1;
            
            $this->orderRepository->update($orderId, [
                'status' => 'delivery_failed',
                'delivery_failed_count' => $newFailedCount,
                'last_delivery_failed_at' => now()
            ]);
            
            // Cập nhật trạng thái bom hàng cho số điện thoại này
            $this->updateBomHangStatus($order->phone);
            
            \Log::info('Marked delivery failed:', [
                'order_id' => $orderId,
                'phone' => $order->phone,
                'failed_count' => $newFailedCount,
                'reason' => $reason
            ]);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error marking delivery failed:', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * ✅ THÊM MỚI: Cập nhật trạng thái đơn hàng và kiểm tra bom hàng
     */
    public function updateOrderStatus($orderId, $status, $reason = null)
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->findById($orderId);
            
            if (!$order) {
                throw new \Exception('Không tìm thấy đơn hàng');
            }
            
            $updateData = ['status' => $status];
            
            // Nếu trạng thái là giao hàng thất bại, tăng số lần thất bại
            if ($status === 'delivery_failed') {
                $newFailedCount = $order->delivery_failed_count + 1;
                $updateData['delivery_failed_count'] = $newFailedCount;
                $updateData['last_delivery_failed_at'] = now();
                
                \Log::info('Updating order status to delivery_failed:', [
                    'order_id' => $orderId,
                    'phone' => $order->phone,
                    'failed_count' => $newFailedCount,
                    'reason' => $reason
                ]);
            }
            
            $this->orderRepository->update($orderId, $updateData);
            
            // Cập nhật trạng thái bom hàng cho số điện thoại này
            $this->updateBomHangStatus($order->phone);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating order status:', [
                'order_id' => $orderId,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * ✅ Cập nhật chỉ trạng thái đơn hàng (không cập nhật order details)
     */
    public function updateOrderStatusOnly($request, $id)
    {
        DB::beginTransaction();
        try {
            $updateOrder = $this->updateOrder($request, (int) $id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating order status:', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }


    private function UpdateOrderDetail($request, $id)
    {
        $payload = $request->only('quantity', 'sku', 'product_id', 'name_orderDetail', 'price');
        $check = null;

        foreach ($payload['sku'] as $key => $value) {
            $data = [
                'order_id' => (int) $id,
                'product_id' => (int) $payload['product_id'][$key],
                'sku' => $value,
                'name' => $payload['name_orderDetail'][$key],
                'quantity' => (int) $payload['quantity'][$key],
                'price' => (float) $payload['price'][$key],
            ];

            $check = $this->orderDetailsRepository->updateOrCreate(
                [
                    "order_id" => $id,
                    "sku" => $value
                ],
                $data
            );
        }

        return $check;
    }


    public function updatePaymentStatus($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method']);
            // dd($payload);
            $result = $this->orderRepository->update($id, $payload);
            // dd($result);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            // return false;
        }
    }


    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // $this->orderRepository->delete($id);
            $this->orderRepository->update($id, ['deleted_at' => now()]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->orderRepository->restore($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->orderRepository->destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

    // ========================
    public function orderStatistic()
    {
        // Cache thống kê trong 5 phút để tránh tính toán lại liên tục
        $cacheKey = 'order_statistics_' . now()->format('Y-m-d-H') . '_' . floor(now()->minute / 5);

        return Cache::remember($cacheKey, 300, function () { // 5 phút
            $month = now()->month;
            $year = now()->year;
            $previousMonth = ($month == 1) ? 12 : $month - 1;
            $previousYear = ($month == 1) ? $year - 1 : $year;
            $result = $this->orderRepository->getOrderByTime($month, $year, $previousMonth, $previousYear);
            return $result;
        });
    }

    public function cancelOrder($id)
{
    DB::beginTransaction();
    try {
        $order = $this->orderRepository->findById($id);
        $this->orderRepository->update($id, ['status' => 'cancelled']);

        // ✅ Cập nhật trạng thái bom hàng sau khi hủy đơn
        if ($order && $order->phone) {
            $this->updateBomHangStatus($order->phone);
        }

        DB::commit();
        return true;
    } catch (\Exception $e) {
        DB::rollback();
        $this->log($e);
        return false;
    }
}

    public function updateQuantityProduct($updateQuantity)
    {
        DB::beginTransaction();
        try {
            foreach ($updateQuantity as $key => $value) {
                $product = $this->productRepository->updateQuantity($value['product_id'], $value['quantity']);
                if (!$product) {
                    $productVariant = $this->productVariantRepository->updateQuantity($value['product_id'], $value['quantity']);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            $this->log($e);
            return false;
        }
    }


}