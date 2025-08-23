@php
    // Đếm số đơn hàng bị đánh dấu bom hàng
    $bomOrders = countBomHang();
@endphp

@if($bomOrders > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-alert-triangle me-2 fs-4"></i>
                <div>
                    <strong>⚠️ CẢNH BÁO BOM HÀNG!</strong>
                    <br>
                    Hiện có <strong>{{ $bomOrders }}</strong> đơn hàng bị đánh dấu là bom hàng (khách hàng hủy từ 2 lần trở lên).
                    <br>
                    <a href="{{ route('order.index') }}" class="alert-link">Xem danh sách đơn hàng</a>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif 