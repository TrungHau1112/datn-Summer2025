<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\VnPayService;
use App\Services\Order\OrderService;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Order\OrderRepository;

class VnPayController extends Controller
{
    protected $vnpayService;
    protected $cartRepository;
    protected $orderRepostitory;
    function __construct(VnPayService $vnpayService, CartRepository $cartRepository, OrderRepository $orderRepostitory)
    {
        $this->vnpayService = $vnpayService;
        $this->cartRepository = $cartRepository;
        $this->orderRepostitory = $orderRepostitory;
    }
    public function pay(Request $request)
    {
        $userId = auth()->id();
        $paymentUrl = $this->vnpayService->createTransaction($request, $userId);
        return redirect($paymentUrl);
    }

    public function payAgain(Request $request)
    {
        $userId = auth()->id();
        $paymentUrl = $this->vnpayService->createAgainTransaction($request, $userId);
        return redirect($paymentUrl);
    }

    public function return(Request $request)
    {
        // dd($request->vnp_TxnRef);
        $code = $request->vnp_TxnRef;
        if ($request->vnp_ResponseCode == "00" && $request->vnp_TransactionNo != null && $request->vnp_ResponseCode == "00") {
            $this->orderRepostitory->updateByWhereIn('code', [$request->vnp_TxnRef], ['payment_status' => 'completed']);
            return view('client.pages.cart.components.checkout.result', [
                'message' => 'Thanh toán thành công, chúng tôi sẽ xử lý đơn hàng của bạn',
                'status' => 'success'
            ]);
        } else {
            // Hash không hợp lệ
            return view('client.pages.cart.components.checkout.result', [
                'message' => 'Thanh toán thất bại',
                'status' => 'error',
                'code' => $code
            ]);
        }
    }

}
