<?php
namespace App\Services\Payment;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Services\Order\OrderService;
use App\Repositories\Order\OrderRepository;


class MomoService extends BaseService
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
            \Log::error('Tạo giao dịch MoMo thất bại: ' . $e->getMessage());
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
            \Log::error('Tạo giao dịch MoMo thất bại: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createPayment($request, $orderV)
    {
        // Lấy thông tin config MoMo
        $partnerCode = config('momo.partner_code');
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        $endpoint = config('momo.endpoint');
        $returnUrl = route('client.checkout.momo.return');
        $notifyUrl = route('client.checkout.momo.notify');

        $orderId = $orderV->code . '_' . time();
        $requestId = $orderId . '_' . time();
        $amount = $orderV->total;
        $orderInfo = "Thanh toán đơn hàng " . $orderId;
        $requestType = "payWithATM"; //captureWallet, payWithATM, payWithMomo, payWithMomoQR
        $extraData = "";

        // Tạo signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $notifyUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $returnUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Shop Phone",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        if (isset($jsonResult['payUrl'])) {
            return $jsonResult['payUrl'];
        } else {
            throw new \Exception('Không thể tạo URL thanh toán MoMo');
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
