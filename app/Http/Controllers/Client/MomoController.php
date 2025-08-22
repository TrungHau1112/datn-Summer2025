<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\MomoService;
use App\Services\Order\OrderService;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Order\OrderRepository;

class MomoController extends Controller
{
    protected $momoService;
    protected $cartRepository;
    protected $orderRepostitory;

    function __construct(MomoService $momoService, CartRepository $cartRepository, OrderRepository $orderRepostitory)
    {
        $this->momoService = $momoService;
        $this->cartRepository = $cartRepository;
        $this->orderRepostitory = $orderRepostitory;
    }

    public function pay(Request $request)
    {
        $userId = auth()->id();
        $paymentUrl = $this->momoService->createTransaction($request, $userId);
        return redirect($paymentUrl);
    }

    public function payAgain(Request $request)
    {
        $userId = auth()->id();
        $paymentUrl = $this->momoService->createAgainTransaction($request, $userId);
        return redirect($paymentUrl);
    }

    public function return(Request $request)
    {
        $orderId = $request->orderId;
        // xóa từ dấu _ trở đi
        $orderId = substr($orderId, 0, strpos($orderId, '_'));
        $resultCode = $request->resultCode;

        if ($resultCode == 0) {
            $this->orderRepostitory->updateByWhereIn('code', [$orderId], ['payment_status' => 'completed']);
            return view('client.pages.cart.components.checkout.result', [
                'message' => 'Thanh toán MoMo thành công, chúng tôi sẽ xử lý đơn hàng của bạn',
                'status' => 'success'
            ]);
        } else {
            return view('client.pages.cart.components.checkout.result', [
                'message' => 'Thanh toán MoMo thất bại',
                'status' => 'error',
                'payment_method' => 'momo',
                'code' => $orderId
            ]);
        }
    }

    public function notify(Request $request)
    {
        $orderId = $request->orderId;
        $resultCode = $request->resultCode;

        if ($resultCode == 0) {
            $this->orderRepostitory->updateByWhereIn('code', [$orderId], ['payment_status' => 'completed']);
        }

        return response()->json(['status' => 'success']);
    }
}
