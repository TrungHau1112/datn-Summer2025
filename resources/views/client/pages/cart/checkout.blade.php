@extends('client.layout')

@push('script')
    <script src="/admin_asset/library/location.js"></script>
    <script src="/client_asset/custom/js/cart/checkout.js"></script>
@endpush

@push('style')
<style>
    /* Styling cho trường phone */
    #customer_phone {
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }
    
    #customer_phone:invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    #customer_phone:valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    /* Hiển thị placeholder khi input rỗng */
    #customer_phone:placeholder-shown {
        border-color: #ced4da;
        box-shadow: none;
    }
    
    /* Animation cho validation */
    .form-group.mb-3 {
        transition: all 0.3s ease;
    }
    
    .form-group.mb-3.has-error {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>
@endpush

@section('content')
<div class="section-box">
    <div class="breadcrumbs-div">
        <div class="container">
            <ul class="breadcrumb">
                <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                <li><a class="font-xs color-gray-500" href="{{ route('client.cart.index') }}">Giỏ hàng</a></li>
                <li><a class="font-xs color-gray-500" href="#">Thanh toán</a></li>
            </ul>
        </div>
    </div>
</div>

<section class="checkout">
    <form class="form_payment" action="{{ route('client.checkout.store') }}" method="post">
        @csrf
        <div class="container my-3">
            <div class="row">
                {{-- Bên trái --}}
                <div class="main-left col-xxl-8 col-md-12 col-sm-12">
                    <div class="card-body">
                        <div class="alert alert-primary" role="alert">
                            <strong>Lưu ý:</strong> <span class="text-danger">(*)</span> là trường bắt buộc nhập
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="customer_name">Tên khách hàng: <span class="text-danger">*</span></label>
                                    <input type="text" id="customer_name" name="name" class="form-control"
                                        value="{{ $user->name }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="customer_phone">Phone: <span class="text-danger">*</span></label>
                                    <input type="tel" id="customer_phone" name="phone" class="form-control"
                                        value="{{ $user->phone }}" 
                                        pattern="[0-9]{10,11}" 
                                        maxlength="11"
                                        placeholder="Nhập số điện thoại"
                                        required>
                                    <small class="form-text text-muted">Ví dụ: 0123456789 hoặc 0987654321</small>
                                </div>
                            </div>
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="tab_location">
                            @include('client.pages.cart.components.checkout.location')
                            <div class="form-group mb-3">
                                <label for="address">Địa chỉ chi tiết</label>
                                <input type="text" name="address" class="form-control" placeholder="Số nhà, ngõ, ..."
                                    required>
                            </div>
                        </div>

                        {{-- Ghi chú --}}
                        <div class="form-group mb-3">
                            <label for="customer_note">Ghi chú:</label>
<textarea name="note" class="form-control" placeholder="Ghi chú cho đơn hàng">{{ $user->note }}</textarea>
                        </div>

                        {{-- Phương thức thanh toán --}}
                        <div class="row mt-3">
                            <h4>Phương thức thanh toán</h4>
                            <div class="col-12">
                                @php
                                    $methods = [
                                        ['id' => 1, 'label' => 'Thanh toán khi nhận hàng', 'image' => 'https://cdn-icons-png.flaticon.com/512/3692/3692056.png', 'route' => route('client.checkout.store')],
                                        ['id' => 2, 'label' => 'Thanh toán bằng VN Pay', 'image' => asset('uploads/image/system/logo_vnpay.png'), 'route' => route('client.checkout.vnpay.pay')],
                                        ['id' => 3, 'label' => 'Thanh toán bằng Momo', 'image' => 'https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png', 'route' => route('client.checkout.momo.pay')],
                                    ];
                                @endphp

                                @foreach($methods as $method)
                                    <div class="form-check ps-0 mb-3">
                                        <label for="payment_method_id{{ $method['id'] }}"
                                            class="label_input_tgnt d-flex justify-content-between align-items-center w-100 p-3 rounded border bg-light cursor-pointer transition-all position-relative payment-option-label">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $method['image'] }}" alt="{{ $method['label'] }}" class="payment-icon">
                                                <span class="w-100 text-center">{{ $method['label'] }}</span>
                                            </div>
                                            <input id="payment_method_id{{ $method['id'] }}"
                                                data-url="{{ $method['route'] }}"
                                                type="radio" name="payment_method_id"
                                                value="{{ $method['id'] }}"
                                                class="form-check-input radio_input_tgnt" required />
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bên phải --}}
                <div class="main-right col-xxl-4 col-md-12 col-12 border rounded h-100">
                    @include('client.pages.cart.components.checkout.cartProduct', $products)

                    @auth
<div class="cart-discount d-flex my-5">
                            <input type="text" class="code-discount form-control w-75 rounded-pill"
                                placeholder="Mã giảm giá" value="">
                            <button type="button" class="apply-discount btn btn-tgnt w-25 ms-2">Áp dụng</button>
                        </div>
                        <div class="list-discount"></div>
                    @endauth

                    @guest
                        <div class="cart-login d-flex my-5">
                            <a href="{{ route('client.auth.login') }}" class="btn btn-tgnt w-100">
                                Đăng nhập để áp dụng mã giảm giá
                            </a>
                        </div>
                    @endguest

                    {{-- ✅ THÊM MỚI: Tính phí ship tự động --}}
                    <div class="shipping-calculation mb-3 p-3 border rounded bg-light">
                        <h6 class="mb-3">
                            <i class="ti ti-truck me-2"></i>
                            Tính phí ship tự động
                        </h6>

                        {{-- Loading state --}}
                        <div id="shippingLoading" class="d-none">
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Đang tính...</span>
                                </div>
                                <span class="text-muted">Đang tính phí ship...</span>
                            </div>
                        </div>

                        <div id="shippingResult" class="d-none">
                            <div class="alert alert-info mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Phí ship:</span>
                                    <strong id="shippingFee">0 ₫</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Thời gian dự kiến:</span>
                                    <strong id="deliveryTime">-</strong>
                                </div>
                            </div>
                        </div>

                        <div id="shippingError" class="alert alert-danger d-none">
                            <i class="ti ti-alert-circle me-1"></i>
                            <span id="shippingErrorMessage"></span>
                        </div>
                    </div>

                    {{-- Tổng tiền --}}
                    <div class="d-flex justify-content-between">
                        <p class="fs-6">Tiết kiệm:</p>
                        <p class="cart-total"><span class="save-price-checkout" id="save-price-checkout"></span>0 ₫</p>
                    </div>

                    <div class="d-flex justify-content-between">
                        <p class="fs-6">Phí ship:</p>
<p class="cart-total"><span id="shippingFeeDisplay">0 ₫</span></p>
                    </div>

                    <div class="d-flex justify-content-between">
                        <p class="fs-6">Thành tiền:</p>
                        <p class="cart-total"><span id="cart-total-discount">{{ formatNumber($total) }}</span> ₫</p>
                    </div>

                    {{-- Nút cuối --}}
                    <div class="cart-last-step d-flex my-4">
                        <a href="{{ route('client.cart.index') }}" class="cart-back btn btn-outline-tgnt w-50 ms-2">Trở lại đơn hàng</a>
                        <div class="value-cart"></div>
                        <button type="submit" class="checkout-cart btn btn-tgnt w-50 ms-2">Đặt hàng</button>

                        {{-- Hidden fields --}}
                        <div class="hidden">
                            <input type="hidden" name="discountCode" class="discount-code" value="">
                            <input type="hidden" name="total_amount" class="total-cart-input" value="{{ $total }}">
                            <input type="hidden" name="total" value="{{ $total }}"> {{-- ✅ Fix lỗi undefined --}}
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <input type="hidden" name="status" value="pending">
                            <input type="hidden" name="payment_status" value="pending">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<style>
    .cart-total {
        color: var(--base-color);
        font-weight: bold;
        font-size: clamp(13px, 1.2vw, 20px);
    }

    .payment-option-label {
        background-color: #f9f9f9;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .payment-option-label:hover,
    .payment-option-label:focus-within {
        background-color: #e0e0e0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .payment-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
    }

    /* Shipping calculation styles */
    .shipping-calculation {
        background: linear-gradient(135deg, #f8f9ff, #f0f4ff) !important;
        border: 1px solid #e3e8ff !important;
    }

    .shipping-calculation h6 {
        color: #2563eb;
        font-weight: 600;
    }

    #shippingLoading {
        background: rgba(37, 99, 235, 0.05);
        border-radius: 8px;
        border: 1px solid rgba(37, 99, 235, 0.1);
    }

    #shippingResult .alert-info {
        background: rgba(37, 99, 235, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.2);
        color: #1e40af;
    }

    #shippingError .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #dc2626;
    }
</style>
@endsection
