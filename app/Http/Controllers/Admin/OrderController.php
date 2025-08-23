<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\order\StoreOrderRequest;
use App\Http\Requests\order\UpdateOrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Location\ProvinceRepository;
use App\Repositories\Location\DistrictRepository;
use App\Repositories\Location\WardRepository;
use App\Services\Order\OrderService;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Payment\PaymentMethodRepository;

class OrderController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Order');
    }

    protected $order;
    protected $orderService;
    protected $orderRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;
    protected $categoryRepository;
    protected $productRepository;
    protected $productVariantRepository;
    protected $paymentMethodRepository;

    public function __construct(
        Order $order,
        OrderService $orderService,
        OrderRepository $orderRepository,
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
        WardRepository $wardRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        PaymentMethodRepository $paymentMethodRepository
    ) {
        $this->order = $order;
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.order.index', compact('orders', 'config'));
    }

    public function getData($request)
    {
        $orders = $this->orderService->paginate($request);
        $config = $this->config();
        return view('admin.pages.order.components.table', compact('orders', 'config'));
    }

    public function create()
    {
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $categories = $this->categoryRepository->findByField('is_room', 2)->pluck('name', 'id')->prepend('Danh mục', 0)->toArray();
        $categoryRoom = $this->categoryRepository->findByField('is_room', 1)->pluck('name', 'id')->prepend('Phòng', 0)->toArray();
        $paymentMethods = $this->paymentMethodRepository->getAllPublic();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';

        return view('admin.pages.order.create', compact(
            'provinces',
            'districts',
            'wards',
            'config',
            'categories',
            'categoryRoom',
            'paymentMethods'
        ));
    }

    public function store(StoreOrderRequest $request)
    {
        // Debug: Có thể bật lại khi cần debug
        \Log::info('Order store request data:', $request->all());
        
        $phone = $request->input('phone');

        // ✅ Logic mới: Kiểm tra giao hàng thất bại thay vì đơn hủy
        $totalDeliveryFailed = Order::where('phone', $phone)
            ->where('delivery_failed_count', '>', 0)
            ->sum('delivery_failed_count');

        \Log::info('Delivery failed count for phone:', [
            'phone' => $phone,
            'total_delivery_failed' => $totalDeliveryFailed
        ]);

        // Tạo đơn hàng
        $order = $this->orderService->create($request);

        if ($order) {
            // Lấy thông báo cảnh báo nếu có
            $warning = getDeliveryFailedWarning($phone);
            
            if ($warning) {
                $message = 'Tạo đơn hàng thành công. ' . $warning;
                return redirect()->route('order.index')->with('warning', $message);
            } else {
                return redirect()->route('order.index')->with('success', 'Tạo đơn hàng mới thành công');
            }
        }

        return redirect()->route('order.index')->with('error', 'Tạo đơn hàng mới thất bại');
    }

    public function edit(string $id)
    {
        $order = $this->orderRepository->findById($id, ['orderDetails.product']);
        $order_details = $order->orderDetails;
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $config = $this->config();
        $paymentMethods = $this->paymentMethodRepository->getAllPublic();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $address = $order->address ?? $order->user->address ?? '';
        return view('admin.pages.order.edit', compact(
            'order',
            'order_details',
            'provinces',
            'districts',
            'wards',
            'address',
            'config',
            'paymentMethods'
        ));
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        // Log để debug
        \Log::info('Order update request:', [
            'order_id' => $id,
            'phone' => $request->phone,
            'status' => $request->status
        ]);

        // Cập nhật đơn hàng (logic bom hàng đã được xử lý trong OrderService)
        $result = $this->orderService->update($request, $id);

        if ($result) {
            return redirect()->route('order.index')->with('success', 'Cập nhật đơn hàng thành công.');
        } else {
            return redirect()->route('order.index')->with('error', 'Cập nhật đơn hàng thất bại');
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $result = $this->orderService->updatePaymentStatus($request, $id);
        return $result ? successResponse() : errorResponse();
    }

    public function dataProduct(Request $request)
    {
        $query = $request->query('query', '');
        $page = $request->query('page', 1);
        $perPage = 5;

        $products = Product::where('name', 'like', "%$query%")->paginate($perPage);

        return response()->json([
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'pages' => $products->lastPage(),
            ],
        ]);
    }

    public function dataVariantsProduct($id)
    {
        $product = $this->productRepository->findById($id, ['productVariants']);
        return successResponse($product->productVariants);
    }

    public function searchCustomer(Request $request)
    {
        $phone = $request->get('phone');
        $customer = Order::where('phone', $phone)->first();

        if ($customer) {
            $latestOrder = Order::where('phone', $phone)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($latestOrder) {
                $location = [
                    'province' => $latestOrder->province_id,
                    'district' => $latestOrder->district_id,
                    'ward' => $latestOrder->ward_id,
                ];
            }
            return successResponse([
                'success' => true,
                'customer' => [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'province_id' => $customer->province_id,
                    'district_id' => $customer->district_id,
                    'ward_id' => $customer->ward_id,
                    'location' => $location ?? null,
                ],
            ]);
        }

        return errorResponse('Không tìm thấy khách hàng');
    }

    public function show(string $id)
    {
        $order = $this->orderRepository->findById($id, ['orderDetails']);
        $order_details = $order->orderDetails;
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('show');
        $address = $order->address ?? $order->user->address ?? '';
        $ward = $order->ward ?? '';
        $district = $order->district ?? '';
        $province = $order->province ?? '';

        return view('admin.pages.order.show', compact(
            'order',
            'order_details',
            'provinces',
            'districts',
            'wards',
            'address',
            'ward',
            'district',
            'province',
            'config'
        ));
    }

    public function getProduct(Request $request)
    {
        $query = $request->query('query', '');
        $products = Product::where('name', 'like', "%$query%")->limit(10)->get();
        return response()->json($products);
    }

    /**
     * ✅ Kiểm tra bom hàng cho số điện thoại - Logic mới
     */
    public function checkBomHang(Request $request)
    {
        $phone = $request->query('phone');
        
        if (!$phone) {
            return response()->json([
                'error' => 'Số điện thoại không được để trống'
            ], 400);
        }
        
        // Sử dụng method debug từ OrderService
        $debugInfo = $this->orderService->checkBomHangStatus($phone);
        
        $totalDeliveryFailed = Order::where('phone', $phone)
            ->where('delivery_failed_count', '>', 0)
            ->sum('delivery_failed_count');
            
        $bomOrdersCount = Order::where('phone', $phone)
            ->where('is_bom', true)
            ->count();
            
        return response()->json([
            'phone' => $phone,
            'total_delivery_failed' => $totalDeliveryFailed,
            'bom_orders_count' => $bomOrdersCount,
            'should_be_bom' => $totalDeliveryFailed >= 2,
            'debug_info' => $debugInfo,
            'warning_message' => getDeliveryFailedWarning($phone)
        ]);
    }

    /**
     * ✅ THÊM MỚI: Đánh dấu giao hàng thất bại
     */
    public function markDeliveryFailed(Request $request, $id)
    {
        $reason = $request->input('reason', 'Không có người nhận');
        
        $result = $this->orderService->markDeliveryFailed($id, $reason);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu giao hàng thất bại'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi đánh dấu giao hàng thất bại'
        ], 400);
    }

    public function trash()
    {
        $orders = $this->orderRepository->getOnlyTrashed();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.order.trash', compact('config', 'orders'));
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => ['name' => 'Danh sách đơn hàng', 'list' => ['Danh sách đơn hàng']],
            'update' => ['name' => 'Cập nhật trạng thái đơn hàng', 'list' => ['QL đơn hàng', 'Cập nhật trạng thái']],
            'create' => ['name' => 'Tạo đơn hàng mới', 'list' => ['QL đơn hàng', 'Tạo đơn hàng']],
            'show' => ['name' => 'Thông tin hóa đơn', 'list' => ['Chi tiết đơn hàng', 'Thông tin hóa đơn']],
            'trash' => ['name' => 'Thùng rác', 'list' => ['Thùng rác']],
        ];

        return $breadcrumb[$key];
    }
    
    private function config()
    {
        return [
            'css' => ['admin_asset/css/order.css'],
            'js' => [
                'admin_asset/library/order_product.js',
                'admin_asset/library/order.js',
                'admin_asset/library/location.js',
                'admin_asset/library/delivery_failed.js' // ✅ Thêm JS cho giao hàng thất bại
            ],
            'model' => 'order'
        ];
    }

    /**
     * ✅ BỔ SUNG: API hủy đơn (AJAX)
     * - Đổi trạng thái sang 'cancelled'
     * - Tự động tính lại cờ is_bom cho toàn bộ số điện thoại
     * - Trả JSON để front-end cập nhật UI
     */
    public function cancel(Order $order, Request $request)
    {
        // (Nếu cần) chặn hủy khi không phải chủ đơn hoặc khi không ở trạng thái cho phép
        if (!in_array($order->status, ['pending', 'cho_xu_ly'])) {
            return response()->json(['message' => 'Đơn này không thể hủy.'], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        // Tính lại BOM cho số điện thoại của đơn này
        $this->recalcBomByPhone($order->phone);

        return response()->json(['success' => true]);
    }

    /**
     * ✅ BỔ SUNG: Hàm tính lại is_bom theo số lần giao hàng thất bại của một số điện thoại
     */
    protected function recalcBomByPhone(?string $phone): void
    {
        if (!$phone) return;

        // Đếm tổng số lần giao hàng thất bại
        $totalDeliveryFailed = Order::where('phone', $phone)
            ->where('delivery_failed_count', '>', 0)
            ->sum('delivery_failed_count');

        $isBom = $totalDeliveryFailed >= 2;

        Order::where('phone', $phone)->update(['is_bom' => $isBom]);

        \Log::info('Recalc BOM by phone (delivery failed logic):', [
            'phone' => $phone,
            'total_delivery_failed' => $totalDeliveryFailed,
            'is_bom' => $isBom,
        ]);
    }

    /**
     * ✅ THÊM MỚI: Force update bom hàng cho tất cả đơn hàng
     */
    public function forceUpdateBomHang(Request $request)
    {
        $result = $this->orderService->forceUpdateAllBomHang();
        
        if ($result !== false) {
            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật bom hàng cho {$result} số điện thoại"
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi cập nhật bom hàng'
        ], 400);
    }

    /**
     * ✅ THÊM MỚI: Sửa lại delivery_failed_count cho các đơn hàng có trạng thái delivery_failed
     */
    public function fixDeliveryFailedCount(Request $request)
    {
        $result = $this->orderService->fixDeliveryFailedCount();
        
        if ($result !== false) {
            return response()->json([
                'success' => true,
                'message' => "Đã sửa lại {$result} đơn hàng có trạng thái giao hàng thất bại"
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi sửa delivery_failed_count'
        ], 400);
    }
}
