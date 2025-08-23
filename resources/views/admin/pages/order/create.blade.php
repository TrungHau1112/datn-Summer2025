@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header">
            <h4>Tạo Đơn Hàng Mới</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('order.store') }}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <h5>Tìm khách hàng cũ theo số điện thoại</h5>
                            <div class="btn-search-customer">
                                <input type="text" class="form-control search-customer" placeholder="Nhập số điện thoại">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <x-input :label="'Tên khách hàng'" name="name" :value="old('name')" :required="true" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <x-input :label="'Email'" name="email" :value="old('email')" :required="true" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <x-input :label="'Số điện thoại'" name="phone" :value="old('phone')" :required="true" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_note">Note:</label>
                            <input type="text" id="customer_note" name="note" class="form-control">
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
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" id="payment_method" name="payment_method" class="form-control"> --}}
                        </div>
                    </div>
                    <div class="col-6">
                        <!-- Trạng Thái -->
                        <div class="form-group">
                            <label for="status" class="form-label">Trạng Thái</label>
                            <select name="status" id="status" class="form-control js-choice-order">
                                @foreach (__('order.status') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
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
                    <x-input :label="'Địa chỉ giao hàng'" name="address" :value="old('address')" :required="false" />
                </div>

                @include('admin.pages.order.components.add_product')

            </form>
        </div>
    </div>

    <script>
        let id = {!! json_encode(old('product_id', [])) !!};
        let sku = {!! json_encode(old('sku', [])) !!};
        let name = {!! json_encode(old('name_orderDetail', [])) !!};
        let price = {!! json_encode(old('price', [])) !!};
        let quantity = {!! json_encode(old('quantity', [])) !!};
        let total = {!! json_encode(old('total', [])) !!};
        let thumbnail = {!! json_encode(old('thumbnail', [])) !!};

        let productVariants = [];
        for (let i = 0; i < id.length; i++) {
            productVariants.push({
                id: id[i],
                sku: sku[i],
                name: name[i],
                price: price[i],
                thumbnail: thumbnail[i],
                quantity: quantity[i],
            });
        }
    </script>
    {{-- <script>
        $(document).ready(function() {
            new Choices('.js-choice-order');
            new Choices('.js-choice-province');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script> --}}

@endsection
