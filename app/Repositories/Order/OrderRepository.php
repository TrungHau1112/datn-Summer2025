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
        $results = DB::table('orders')
            ->selectRaw("
                COUNT(*) as total_order,
                SUM(CASE 
                    WHEN payment_status = 'completed' THEN 1 
                    ELSE 0 
                END) as completed_orders,
                SUM(CASE 
                    WHEN payment_status = 'cancelled' THEN 1 
                    ELSE 0 
                END) as cancelled_orders,
                SUM(CASE 
                    WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? AND payment_status = 'completed' THEN 1 
                    ELSE 0 
                END) as current_month_completed_orders,
                SUM(CASE 
                    WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? AND payment_status = 'completed' THEN total 
                    ELSE 0 
                END) as current_month_revenue,
                SUM(CASE 
                    WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? AND payment_status = 'completed' THEN 1 
                    ELSE 0 
                END) as previous_month_completed_orders,
                SUM(CASE 
                    WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? AND payment_status = 'completed' THEN total 
                    ELSE 0 
                END) as previous_month_revenue
            ", [$month, $year, $month, $year, $previousMonth, $previousYear, $previousMonth, $previousYear])
            ->first();
        return [
            'total_order' => $results->total_order,
            'completed_orders' => $results->completed_orders,
            'cancelled_orders' => $results->cancelled_orders,
            'current_month_completed' => $results->current_month_completed_orders,
            'previous_month_completed' => $results->previous_month_completed_orders,
            'current_month_revenue' => $results->current_month_revenue,
            'previous_month_revenue' => $results->previous_month_revenue,
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