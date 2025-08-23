<div class="animate__animated animate__fadeIn">
    <div class="h-100">
        <div class="">
            <div class="d-md-flex justify-content-between">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    {{-- <h4 class="mb-0">Mã đơn hàng: <span class="order_code">{{ $order->code }}</span></h4> --}}
                    <span class="badge bg-light-warning text-dark-warning ms-2">{{ statusOrder($order->status) }}</span>
                </div>
                <!-- button -->
                {{-- @if ($order->status == 'delivered')
                    <div class="ms-md-3">
                        <button href="#" class="btn btn-secondary">Tải hóa đơn</button>
                    </div>
                @endif --}}
            </div>
        </div>
        <div class="mt-8">
            <div class="row">
                <!-- address -->
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="mb-6">
                        <h6>Thông tin giao hàng</h6>
                        <p class="mb-1 lh-lg">
                            Tên người nhận: <span class="text-dark">{{ $order->name }}</span>
                            <br>
                            SĐT: <span class="text-dark">{{ $order->phone }}</span>
                            <br>
                            Địa chỉ: <span class="text-dark">{{ $order->address }}, {{ $order->ward->name }},
                                {{ $order->district->name }}, {{ $order->province->name }}</span>
                        </p>
                    </div>
                </div>
                <!-- address -->
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="mb-6">
                        <h6>Chi tiết đặt hàng</h6>
                        <p class="mb-1 lh-lg">
                            Mã đơn hàng:
                            <span class="text-dark">{{ $order->code }}</span>
                            <br>
                            Ngày đặt hàng:
                            <span class="text-dark">{{ $order->created_at }}</span>
                            <br>
                            Tổng tiền:
                            <span class="text-dark">{{ formatMoney($order->total) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <!-- Table -->
                <table class="table mb-0 text-nowrap table-centered">
                    <!-- Table Head -->
                    <thead class="bg-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá tiền</th>
                            <th>Số lượng</th>
                            <th>Tổng tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <!-- tbody -->
                    <tbody>
                        @foreach ($order->orderDetails as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <img src="{{ $item->product->thumbnail }}" width="100" alt=""
                                                class="icon-shape icon-lg rounded">
                                        </div>
                                        <div class="ms-lg-4 mt-2 mt-lg-0 text-wrap">
                                            <h5 class="mb-0 h6">
                                                <a class="text-tgnt"
                                                    href="{{ route('client.product.detail', $item->product->slug) }}">{{ $item->name }}</a>
                                            </h5>
                                        </div>
                                    </div>

                                </td>
                                <td><span class="text-body">{{ formatMoney($item->price) }}</span></td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ formatMoney($item->quantity * $item->price) }}</td>
                                <td>
                                    @if ($order->status == 'delivered')
                                        @if ($item->product->reviews->where('user_id', Auth::id())->count() == 0)
                                            <button data-product-id="{{ $item->product_id }}" data-bs-toggle="modal"
                                                data-bs-target="#modal_danhgia" class="btn btn-brand-3 ">Viết
                                                đánh giá
                                            </button>
                                        @else
                                            <button data-product-id="{{ $item->product_id }}" data-product-slug="{{ $item->product->slug }}" data-bs-toggle="modal"
                                                data-bs-target="#modal_xemdanhgia"
                                                class="btn btn-brand-3">Xem
                                                đánh
                                                giá</button>
                                        @endif
                                    @else
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td colspan="1" class="fw-semibold text-dark">
                                <!-- text -->
                                Tổng cộng
                            </td>
                            <td class="fw-semibold text-dark">
                                <!-- text -->
                                {{ formatMoney($order->total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0">
                <h6>Thông tin thanh toán: </h6>
                <span>{{ $order->paymentMethod->name }}</span>
                <div class="mt-3">
                    <h6>Trạng thái thanh toán: </h6>
                    <span>{{ paymentStatusOrder($order->payment_status) }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <h5>Ghi chú đơn hàng:</h5>
                <textarea disabled class="form-control mb-3" rows="3" placeholder="">{{ $order->note }}</textarea>
            </div>
        </div>
    </div>
</div>
