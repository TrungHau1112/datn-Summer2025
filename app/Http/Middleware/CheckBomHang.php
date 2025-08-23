<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CheckBomHang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Chỉ kiểm tra khi cập nhật đơn hàng
        if ($request->isMethod('PUT') && $request->route()->getName() === 'order.update') {
            $orderId = $request->route('id');
            $phone = $request->input('phone');
            $status = $request->input('status');
            
            // Kiểm tra bom hàng khi có thay đổi trạng thái và có số điện thoại
            if ($phone) {
                $this->checkAndUpdateBomHang($phone);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Kiểm tra và cập nhật trạng thái bom hàng - Logic mới dựa trên giao hàng thất bại
     */
    private function checkAndUpdateBomHang($phone)
    {
        try {
            // Đếm tổng số lần giao hàng thất bại
            $totalDeliveryFailed = Order::where('phone', $phone)
                ->where('delivery_failed_count', '>', 0)
                ->sum('delivery_failed_count');
                
            // Nếu >= 2 lần giao hàng thất bại, đánh dấu tất cả đơn hàng thành bom hàng
            if ($totalDeliveryFailed >= 2) {
                $updatedCount = Order::where('phone', $phone)->update(['is_bom' => true]);
                
                \Log::info('Auto updated bom orders via middleware (delivery failed logic):', [
                    'phone' => $phone,
                    'total_delivery_failed' => $totalDeliveryFailed,
                    'updated_count' => $updatedCount
                ]);
            } else {
                // Nếu < 2 lần thất bại, bỏ đánh dấu bom hàng
                $updatedCount = Order::where('phone', $phone)->update(['is_bom' => false]);
                
                \Log::info('Removed bom orders via middleware:', [
                    'phone' => $phone,
                    'total_delivery_failed' => $totalDeliveryFailed,
                    'updated_count' => $updatedCount
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in CheckBomHang middleware:', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
        }
    }
}
