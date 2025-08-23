@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header">
            <h4>Chỉnh sửa Đơn Hàng #{{ $order->id }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_name">Tên khách hàng:</label>
                            <input type="text" id="customer_name" name="name" class="form-control"
                                value="{{ $order->name }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_email">Email:</label>
                            <input type="text" id="customer_email" name="email" class="form-control"
                                value="{{ $order->email }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_phone">Phone:</label>
                            <input type="text" id="customer_phone" name="phone" class="form-control"
                                value="{{ $order->phone }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_note">Note:</label>
                            <input type="text" id="customer_note" name="note" class="form-control"
                                value="{{ $order->note }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <!-- Phương Thức Thanh Toán -->
                        <div class="form-group mb-3">
                            <label for="payment_method_id" class="form-label">Phương Thức Thanh Toán:</label>
                            <select class="form-select" name="payment_method_id" id="payment_method_id">
                                @foreach ($paymentMethods as $item)
                                    <option {{ $item->id == $order->payment_method_id }} value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <!-- Trạng Thái -->
                        <div class="form-group">
                            <label for="status" class="form-label">Trạng Thái</label>
                            <select name="status" id="status" class="form-control js-choice-order">
                                @foreach (__('order.status') as $key => $value)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Trạng Thái Thanh Toán -->
                <div class="form-group mb-3">
                    <label for="payment_status" class="form-label required">Trạng Thái Thanh Toán:</label>
                    <select name="payment_status" id="payment_status" class="form-control">
                        @php
                            $paymentStatuses = __('order.payment_status');
                        @endphp

                        @if (is_array($paymentStatuses))
                            @foreach ($paymentStatuses as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        @else
                            <option value="">{{ $paymentStatuses }}</option>
                        @endif
                    </select>
                </div>

                <!-- Địa chỉ giao hàng -->
                @include('admin.pages.order.components.location')
                <div class="form-group mb-3">
                    <x-input :label="'Địa chỉ chi tiết'" name="address" :value="$address" :required="false" />
                </div>

                {{-- Thêm sản phẩm --}}
                @include('admin.pages.order.components.add_product')
            </form>
        </div>

    </div>
    <script></script>

@endsection
