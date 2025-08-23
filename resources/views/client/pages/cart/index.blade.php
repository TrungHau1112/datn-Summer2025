@extends('client.layout')

@section('seo')
    <link rel="stylesheet" href="/client_asset/custom/css/cart.css">
@endsection

@push('script')
    <script src="/client_asset/custom/js/cart/cart.js"></script>
@endpush

@section('content')
    <script>
        let homeUrl = @json(route('client.home'));
    </script>
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li><a class="font-xs color-gray-500" href="#">Giỏ hàng</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="cart">
        <div class="container my-lg-5">
            <p class="fs-1 fw-bold cart-title">Giỏ hàng</p>
            <div class="cart-client row">
                @if (isset($carts) && $carts->count() > 0)
                    <div class="main-left col-xxl-8 col-md-12 col-sm-12">
                        <hr class="border-4 w-25 fw-bold mt-0">
                        <div class="cart-container">
                            <p class="fs-6 my-3">Bạn đang có <span class="fw-bold cart_count">{{ $carts->count() }}</span> sản
                                phẩm
                                trong giỏ hàng</p>
                            <div class="list-cart line-y" id="list-cart">
                                {{-- Render product in cart --}}
                                {!! $listCart !!}
                            </div>
                        </div>
                    </div>
                    <div class="main-right col-xxl-4 col-md-12 col-12 border  rounded h-100">
                        <p class="fs-2 text-dark py-4 fw-bold">Tổng quan đơn hàng</p>
                        <div class="d-flex justify-content-between">
                            <p class="fs-6">Giỏ hàng (<span class="cart_count">{{ $carts->count() }}</span> sản phẩm):</p>
                            <p class="cart-total"><span id="cart-total"></span>₫</p>
                            <input type="hidden" id="cart-total-input" value=""></input>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="fs-6">Thành tiền:</p>
                            <p class="cart-total"><span id="cart-total-discount-collection"></span>₫</p>
                            <input type="hidden" id="cart-total-discount-collection-input" value=""></input>
                        </div>
                        <p class="cart-discount-title mt-3">Thông tin giao hàng</p>
                        <p class="my-2 text">Đối với những sản phẩm có sẵn tại khu vực, "{{ getSetting()->site_name }}" sẽ giao
                            hàng
                            trong vòng 2-7 ngày. Đối với những sản phẩm không có sẵn, thời gian giao hàng sẽ được nhân
                            viên "{{ getSetting()->site_name }}" thông báo đến quý khách.</p>
                        <div class="cart-last-step d-flex my-4">
                            <a href="{{ route('client.home') }}" class="cart-back btn btn-outline-tgnt w-50 ms-2">Tiếp tục
                                mua hàng</a>
                            <div class="value-cart">
                                <input type="hidden" class="total-cart-input" value="">
                            </div>
                            <a href="{{ $carts->count() > 0 ? route('client.checkout.index') : 'javascript:void(0);' }}"
                                class="checkout-cart btn btn-tgnt w-50 ms-2">
                                Đặt hàng
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- slide -->
    {{-- <section class="container mb-5">
        <h3 class="fw-bold pb-4">Sản phẩm đã xem gần đây</h3>
        <div class="row animate__animated animate__fadeIn listProduct mb-4" id="slide-featured">
            @foreach ($product_featureds->take(8) as $product_featured)
                <x-product_card :data="$product_featured" />
            @endforeach
        </div>
    </section> --}}

@endsection