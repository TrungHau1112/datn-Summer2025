<?php
namespace App\Repositories\Order;
use App\Repositories\BaseRepository;
use App\Models\Order;
use DB;

class OrderRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        Order $model
    ) {
        $this->model = $model;
    }

    public function getOrderByTime($month, $year, $previousMonth, $previousYear)
    {
        // Thay vì sử dụng 1 query phức tạp, chia thành các query riêng biệt để tối ưu index
        $baseQuery = DB::table('orders');

        // Query cơ bản để lấy tổng số đơn hàng và trạng thái
        $basicStats = $baseQuery->clone()
            ->selectRaw("
                COUNT(*) as total_order,
                SUM(CASE WHEN payment_status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN payment_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders
            ")
            ->first();

        // Query tối ưu cho tháng hiện tại - sử dụng WHERE thay vì CASE để tận dụng index
        $currentMonth = $baseQuery->clone()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('payment_status', 'completed')
            ->selectRaw('COUNT(*) as completed_orders, SUM(total) as revenue')
            ->first();

        // Query tối ưu cho tháng trước
        $previousMonthStats = $baseQuery->clone()
            ->whereYear('created_at', $previousYear)
            ->whereMonth('created_at', $previousMonth)
            ->where('payment_status', 'completed')
            ->selectRaw('COUNT(*) as completed_orders, SUM(total) as revenue')
            ->first();

        return [
            'total_order' => $basicStats->total_order,
            'completed_orders' => $basicStats->completed_orders,
            'cancelled_orders' => $basicStats->cancelled_orders,
            'current_month_completed' => $currentMonth->completed_orders ?? 0,
            'previous_month_completed' => $previousMonthStats->completed_orders ?? 0,
            'current_month_revenue' => $currentMonth->revenue ?? 0,
            'previous_month_revenue' => $previousMonthStats->revenue ?? 0,
        ];
    }

    public function getOrdersByMonth($year)
    {
        $orders = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', $year)
            ->where('payment_status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_orders', 'month');
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $orders[$i] ?? 0;
        }
        return $data;
    }

    public function ordersAndRevenueByYear($year)
    {
        $data = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders, SUM(total) as monthly_revenue')
            ->whereYear('created_at', $year)
            ->where('payment_status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $data->firstWhere('month', $i);
            $result[] = [
                'month' => $i,
                'total_orders' => $monthData->total_orders ?? 0,
                'monthly_revenue' => $monthData->monthly_revenue ?? 0,
            ];
        }

        return response()->json($result);
    }

    public function getOrdersByStatus($userId, $status, $paginate = 10)
    {
        return $this->model->where('status', $status)->where('user_id', $userId)->with('orderDetails')->get();
    }

    public function getOrdersByUser($userId)
    {
        return $this->model->where('user_id', $userId)->with('orderDetails')->get();
    }

    public function getOrderByCode($code)
    {
        return $this->model->where('code', $code)->with('orderDetails', 'user')->first();
    }

    public function getOrderPaymentPending($userId)
    {
        return $this->model->where('payment_status', 'pending')->where('user_id', $userId)->whereHas('paymentMethod', function ($query) {
            $query->where('type', 'online');
        })->get();
    }



}