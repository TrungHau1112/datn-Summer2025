@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="invoice">
        <div class="card border-primary">
            <div class="card-header bg-light-primary text-white text-center p-3">
                <h2>HÓA ĐƠN BÁN HÀNG</h2>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <p class="font-weight-bold text-uppercase mb-0">Công Ty TNHH {{ getSetting()->site_name }}</p>
                    <p class="mb-0 text-secondary">Địa chỉ: 72 Nguyễn Thị Thập, Liên Chiểu, Đà Nẽng, Việt Nam</p>
                    <p class="mb-0 text-secondary">Điện thoại: +(84) 878 006 779</p>
                </div>

                <div class="d-flex justify-content-end text-primary mb-4">
                    <div>
                        <p>Ngày: <span class="font-weight-bold">{{ now()->day }} tháng {{ now()->month }} năm
                                {{ now()->year }}</span></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <h6 class="text-primary">Thông tin người mua hàng (Buyer):</h6>
                        <p class="mb-1"><strong>Họ & Tên:</strong> {{ $order->name }}</p>
                        <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                        <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward->name }},
                            {{ $order->district->name }}, {{ $order->province->name }}</p>

                    </div>
                    <div class="col-6 text-right">
                        <h6 class="text-primary">Thông tin thanh toán</h6>
                        <p class="mb-1"><strong>Hình thức thanh toán:</strong> {{ $order->paymentMethod->name }}</p>
                        <p class="mb-1"><strong>Trạng thái thanh toán:</strong>
                            {{ paymentStatusOrder($order->payment_status) }}</p>
                    </div>
                </div>

                <h5 class="mt-4 text-primary">Chi tiết Đơn Hàng</h5>
                <table class="table table-bordered text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>STT</th>
                            <th>Tên hàng hóa, dịch vụ</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_details as $index => $detail)
                            @php
                                $lineTotal = $detail->price * $detail->quantity;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ formatMoney($detail->price) }}</td>
                                <td>{{ formatMoney($lineTotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-6">
                        {{-- <p class="text-primary"><strong>Tỷ giá quy đổi:</strong> 1 USD = 23,080 VND</p> --}}
                    </div>
                    <div class="col-6 text-right">
                        <p class="font-weight-bold text-primary"><strong>Tổng tiền thanh toán :
                                {{ formatMoney($order->total) }}</strong></p>
                        <p class="font-italic text-secondary">Số tiền viết bằng chữ: .....</p>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-6 text-center">
                        <p class="text-primary font-weight-bold">Người mua hàng</p>
                        <p>(Ký, ghi rõ họ, tên)</p>
                    </div>
                    <div class="col-6 text-center">
                        <p class="text-primary font-weight-bold">Người bán hàng</p>
                        <p>(Ký, ghi rõ họ, tên)</p>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="text-end">
                    <a href="{{ route('order.index') }}" class="btn btn-outline-danger">Quay lại</a>
                    <button type="button" class="btn btn-primary" onclick="window.print()">In hóa đơn</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .card.border-primary,
            .card.border-primary * {
                visibility: visible;
            }

            .card.border-primary {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .card-footer {
                display: none;
            }
        }
    </style>
@endsection
