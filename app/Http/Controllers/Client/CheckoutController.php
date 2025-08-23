<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Cart\CartRepository;
use App\Services\Cart\CartService;
use App\Services\Order\OrderService;
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
        UserRepository $userRepository,
        DiscountCodeRepository $discountCodeRepository,
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
        WardRepository $wardRepository,
        PaymentMethodRepository $paymentMethodRepository
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
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
        $address = $order->address ?? $order->user->address ?? '';
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
        $existCode = $this->cartService->checkDiscount(Auth::id(), $request->code);
        if (!$existCode && !checkExpiredDate($discountCode->end_date)) {
            return successResponse($discountCode, '');
        }
    }

    public function applyDiscount(Request $request)
    {
        $discountCode = $this->discountCodeRepository->findByField('code', $request->code)->first();
        if (!checkExpiredDate($discountCode->end_date)) {
            return $discountCode;
        }
        return false;
    }
    public function store(Request $request)
    {
        $order = $this->orderService->create($request);
        if ($order) {
            if (isset($request->discountCode)) {
                $this->cartService->submitDiscount(Auth::id(), $request->discountCode);
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
}
