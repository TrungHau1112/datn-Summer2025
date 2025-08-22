<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Cart\CartRepository;
use App\Services\Cart\CartService;
use App\Services\Order\OrderService;
use App\Services\Ghtk\GhtkService;
use App\Repositories\User\UserRepository;
use App\Repositories\Discount\DiscountCodeRepository;
use App\Repositories\Location\ProvinceRepository;
use App\Repositories\Location\DistrictRepository;
use App\Repositories\Location\WardRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Jobs\SendOrderMail;
use App\Jobs\SendTelegramNotification;
class CheckoutController extends Controller
{
    protected $cartRepository;
    protected $cartService;
    protected $orderService;
    protected $ghtkService;
    protected $userRepository;
    protected $discountCodeRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;
    protected $paymentMethodRepository;
    public function __construct(
        CartRepository $cartRepository,
        CartService $cartService,
        OrderService $orderService,
        GhtkService $ghtkService,
        UserRepository $userRepository,
        DiscountCodeRepository $discountCodeRepository,
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
        WardRepository $wardRepository,
        PaymentMethodRepository $paymentMethodRepository
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->ghtkService = $ghtkService;
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
        $this->discountCodeRepository = $discountCodeRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->cartRepository = $cartRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }
    public function index()
    {
        if (Auth::id()) {
            $user = $this->userRepository->findById(Auth::id());
        }
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        if ($carts->isEmpty()) {
            return redirect()->route('client.cart.index');
        }
        $products = $this->cartService->fetchCartData($carts)['cart'];
        $total = $this->cartService->fetchCartData($carts)['total'];
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $config = $this->config();
        
        // ✅ Sửa lỗi: Biến $order chưa được định nghĩa
        $address = $user->address ?? '';
        
        $orderPayment = $this->paymentMethodRepository->getAllPublic();
        return view('client.pages.cart.checkout', compact(
            'user',
            'provinces',
            'districts',
            'wards',
            'address',
            'products',
            'total',
            'config',
            'orderPayment'
        ));
    }
    public function addDiscount(Request $request)
    {
        $discountCode = $this->discountCodeRepository->findByField('code', $request->code)->first();
        
        // Kiểm tra mã giảm giá có tồn tại không
        if (!$discountCode) {
            return errorResponse('Mã giảm giá không tồn tại');
        }
        
        // Kiểm tra mã giảm giá có thể sử dụng không (bao gồm lượt sử dụng)
        if (!$discountCode->canBeUsed()) {
            if (!$discountCode->publish) {
                return errorResponse('Mã giảm giá không hoạt động');
            }
            if (checkExpiredDate($discountCode->end_date)) {
                return errorResponse('Mã giảm giá đã hết hạn');
            }
            if (!$discountCode->hasRemainingUsage()) {
                return errorResponse('Mã giảm giá đã hết lượt sử dụng');
            }
            return errorResponse('Mã giảm giá không hợp lệ');
        }
        
        // Kiểm tra mã giảm giá đã được sử dụng chưa
        $existCode = $this->cartService->checkDiscount(Auth::id(), $request->code);
        if ($existCode) {
            return errorResponse('Mã giảm giá đã được sử dụng');
        }
        
        // Lấy tổng giá trị giỏ hàng
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        $cartTotal = $this->cartService->getTotalCart($carts);
        
        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($discountCode->min_order_amount && $cartTotal < $discountCode->min_order_amount) {
            return errorResponse("Mã giảm giá yêu cầu đơn hàng tối thiểu " . number_format($discountCode->min_order_amount) . " VNĐ");
        }
        
        // Kiểm tra trừ tiền âm
        $discountAmount = 0;
        if ($discountCode->discount_type === 'percentage') {
            // Giảm giá theo phần trăm
            $discountAmount = ($cartTotal * $discountCode->discount_value) / 100;
        } else {
            // Giảm giá theo số tiền cố định
            $discountAmount = $discountCode->discount_value;
        }
        
        // Nếu giảm giá quá nhiều, gây ra số âm
        if ($discountAmount >= $cartTotal) {
            return errorResponse("Mã giảm giá không thể áp dụng vì sẽ gây ra số tiền âm");
        }
        
        return successResponse($discountCode, 'Mã giảm giá hợp lệ');
    }

    public function applyDiscount(Request $request)
    {
        $discountCode = $this->discountCodeRepository->findByField('code', $request->code)->first();
        
        // Kiểm tra mã giảm giá có tồn tại không
        if (!$discountCode) {
            return false;
        }
        
        // Kiểm tra mã giảm giá có thể sử dụng không (bao gồm lượt sử dụng)
        if (!$discountCode->canBeUsed()) {
            return false;
        }
        
        // Kiểm tra mã giảm giá đã được sử dụng chưa
        $existCode = $this->cartService->checkDiscount(Auth::id(), $request->code);
        if ($existCode) {
            return false;
        }
        
        // Lấy tổng giá trị giỏ hàng
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        $cartTotal = $this->cartService->getTotalCart($carts);
        
        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($discountCode->min_order_amount && $cartTotal < $discountCode->min_order_amount) {
            return false;
        }
        
        // Kiểm tra trừ tiền âm
        $discountAmount = 0;
        if ($discountCode->discount_type === 'percentage') {
            // Giảm giá theo phần trăm
            $discountAmount = ($cartTotal * $discountCode->discount_value) / 100;
        } else {
            // Giảm giá theo số tiền cố định
            $discountAmount = $discountCode->discount_value;
        }
        
        // Nếu giảm giá quá nhiều, gây ra số âm
        if ($discountAmount >= $cartTotal) {
            return false;
        }
        
        return $discountCode;
    }
    public function store(Request $request)
    {
        $order = $this->orderService->create($request);
        if ($order) {
            if (isset($request->discountCode)) {
                $this->cartService->submitDiscount(Auth::id(), $request->discountCode);
                
                // Tăng lượt sử dụng mã giảm giá
                if ($request->discountCode) {
                    $discountCode = $this->discountCodeRepository->findByField('code', $request->discountCode)->first();
                    if ($discountCode) {
                        $discountCode->incrementUsage();
                    }
                }
            }
            return view('client.pages.cart.components.checkout.result', ['message' => 'Đặt hàng thành công', 'status' => 'success']);
        }
        return view('client.pages.cart.components.checkout.result', ['message' => 'Đặt hàng thất bại', 'status' => 'success']);
    }
    private function config()
    {
        return [
            'css' => [

            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
                'admin_asset/library/location.js',
                "client_asset/custom/js/cart/checkout.js",
            ],
            'model' => 'checkout'
        ];
    }

    /**
     * ✅ THÊM MỚI: Tính phí ship tự động từ GHTK
     */
    public function calculateShippingFee(Request $request)
    {
        $request->validate([
            'pickup_province' => 'required|string',
            'pickup_district' => 'required|string',
            'delivery_province' => 'required|string',
            'delivery_district' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'value' => 'nullable|numeric|min:0',
        ]);

        try {
            $pickupAddress = [
                'province' => $request->pickup_province,
                'district' => $request->pickup_district,
            ];

            $deliveryAddress = [
                'province' => $request->delivery_province,
                'district' => $request->delivery_district,
            ];

            $result = $this->ghtkService->calculateShippingFee(
                $pickupAddress,
                $deliveryAddress,
                $request->weight,
                $request->value ?? 0
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'shipping_fee' => $result['fee'],
                    'insurance_fee' => $result['insurance_fee'],
                    'total_fee' => $result['total_fee'],
                    'estimated_deliver_time' => $result['estimated_deliver_time'],
                    'delivery_type' => $result['delivery_type'],
                    'message' => 'Tính phí ship thành công!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Không thể tính phí ship'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ THÊM MỚI: Lấy danh sách tỉnh thành từ GHTK
     */
    public function getGhtkProvinces()
    {
        try {
            $provinces = $this->ghtkService->getProvinces();
            
            return response()->json([
                'success' => true,
                'data' => $provinces
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách tỉnh thành: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ THÊM MỚI: Lấy danh sách quận huyện từ GHTK
     */
    public function getGhtkDistricts(Request $request)
    {
        $request->validate([
            'province_code' => 'required|string'
        ]);

        try {
            $districts = $this->ghtkService->getDistricts($request->province_code);
            
            return response()->json([
                'success' => true,
                'data' => $districts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách quận huyện: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ THÊM MỚI: Lấy danh sách phường xã từ GHTK
     */
    public function getGhtkWards(Request $request)
    {
        $request->validate([
            'district_code' => 'required|string'
        ]);

        try {
            $wards = $this->ghtkService->getWards($request->district_code);
            
            return response()->json([
                'success' => true,
                'data' => $wards
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách phường xã: ' . $e->getMessage()
            ], 500);
        }
    }
}
