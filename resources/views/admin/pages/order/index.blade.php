@extends('admin.layout')
@section('template')
<x-breadcrumb :breadcrumb="$config['breadcrumb']" />
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Cảnh báo bom hàng --}}
@php
    $bomOrders = countBomHang();
@endphp
@if($bomOrders > 0)
<div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
    <div class="d-flex align-items-center">
        <i class="ti ti-alert-triangle me-2"></i>
        <div>
            <strong>⚠️ CẢNH BÁO BOM HÀNG!</strong>
            Hiện có <strong>{{ $bomOrders }}</strong> đơn hàng bị đánh dấu là bom hàng.
            <br>
            <small class="text-muted">Các đơn hàng này có khách hàng giao hàng thất bại từ 2 lần trở lên.</small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- ✅ THÊM CẢNH BÁO DELIVERY FAILED --}}
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
        <i class="ti ti-alert-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">

    {{-- ✅ Hiển thị cảnh báo thành công / thất bại --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <x-filter :model="$config['model']" :createButton="[
                'label' => '',
                'route' => $config['model'] . '.create',
            ]" :options="[
                'actions' => generateSelect('Hành động', __('order.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                // 'publish' => generateSelect('Trạng thái', __('order.statusFilter')),
                'sort' => generateSelect('Sắp xếp', __('order.sort')),
            ]" />
            
            {{-- ✅ THÊM NÚT FORCE UPDATE BOM HÀNG --}}
            <button type="button" class="btn btn-warning btn-sm me-2" id="forceUpdateBomHang">
                <i class="ti ti-refresh me-1"></i>
                Cập nhật Bom Hàng
            </button>
            
            {{-- ✅ THÊM NÚT SỬA LỖI DELIVERY FAILED COUNT --}}
            <button type="button" class="btn btn-danger btn-sm" id="fixDeliveryFailedCount">
                <i class="ti ti-wrench me-1"></i>
                Sửa lỗi Bom Hàng
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll"></label>
                            </div>
                        </th>
                        <th>STT</th>
                        <th>Mã đơn hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Phương Thức Thanh Toán</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Trạng Thái</th>
                        <th>Bom Hàng</th>
                        <th>Ngày Tạo</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @include('admin.pages.order.components.table')
                </tbody>
            </table>
        </div>
    </div>
</div>

<input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
