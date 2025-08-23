@extends('admin.layout')
@section('template')
<x-breadcrumb :breadcrumb="$config['breadcrumb']" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card">
    <div class="card-header">
        <x-filter :model="$config['model']" :createButton="[
        'label' => '',
        'route' => $config['model'] . '.create',
        ]" :options="[
            'actions' => generateSelect('Hành động', __('order.actions')),
            'perpage' => generateSelect('10 hàng', __('general.perpage')),
            // 'publish' => generateSelect('Trạng thái', __('order.statusFilter')),
            'sort' => generateSelect('Sắp xếp', __('order.sort')),
        ]" />
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
