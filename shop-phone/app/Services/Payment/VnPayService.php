<?php
namespace App\Services\Payment;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Services\Order\OrderService;
use App\Repositories\Order\OrderRepository;


class VnPayService extends BaseService
{
    protected $orderService;
    protected $orderRepository;

    public function __construct(OrderService $orderService, OrderRepository $orderRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    public function createTransaction($request, $userId)
    {
        DB::beginTransaction();
        try {
            $request->payment_method_id = 2;
            $order = $this->orderService->create($request);
            $paymentUrl = $this->createPayment($request, $order);
            DB::commit();
            return $paymentUrl;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Tạo giao dịch thất bại: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createAgainTransaction($request, $userId)
    {
        try {
            $order = $this->orderRepository->findByField('code', $request->code)->first();
            $paymentUrl = $this->createPayment($request, $order);
            return $paymentUrl;
        } catch (\Exception $e) {
            \Log::error('Tạo giao dịch thất bại: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createPayment($request, $orderV)
    {
        // Lấy thông tin config: 
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_ReturnUrl = route('client.checkout.vnpay.return');

        $order = (object) [
            "code" => $orderV->code,
            "total" => $orderV->total,
            "bankCode" => 'NCB',
            "type" => "payWithATM", //billpayment, payWithATM, payWithMomo, payWithMomoQR
            "info" => "Thanh toán đơn hàng " . $orderV->code
        ];

        $vnp_TxnRef = $order->code;
        $vnp_OrderInfo = $order->info;
        $vnp_OrderType = $order->type;
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = $order->bankCode;
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }
}
