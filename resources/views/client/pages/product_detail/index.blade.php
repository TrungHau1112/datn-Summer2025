@extends('client.layout')
@section('seo')
    @include('client.components.seo')
@endsection
@push('script')
    <script src="/client_asset/custom/js/cart/addToCart.js"></script>
    <script src="/client_asset/custom/js/product/comment_review.js"></script>
    <script src="/client_asset/custom/js/product/attribute.js"></script>
@endpush
@section('content')
<!-- abc -->
@php
    $name = $product->name;
    $price = $product->price;
    $discount = $product->discount;
    $priceDiscount = $price - ($price * $discount) / 100;
    $albums = $product->albums;
    $description = $product->description;
    $category = $product->categories;
    $attributeCategory = $product->attribute_category;
    $shortContent = $product->short_content;
    $attrUrl = $_GET['attr'] ?? '';
    if ($attrUrl) {
        $attrUrl = explode(',', $attrUrl);
    }

@endphp

@section('content')
    <script>
        const product_id = {{ $product->id ?? 0 }};
    </script>
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li><a class="font-xs color-gray-500" href="{{ route('client.product.index') }}">Sản phẩm</a></li>
                    <li><a class="font-xs color-gray-500" href="#">{{ $name }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template product_ct">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="color-brand-3 mb-5 mw-80">{{ $name }}</h3>
                    <div class="row">
                        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-7 text-center text-sm-start mb-mobile">
                            <div class="rating mt-5 d-inline-block mr-20"><span
                                    class="font-sm color-brand-3 font-medium">SKU:</span><span
                                    class="font-sm color-gray-500">{{ $product->sku }}</span></div>
                        </div>
                        <div class="col-xl-6 col-lg-5 col-md-4 col-sm-5 text-center text-sm-end">
                            <div class="d-inline-block"><a href="#">
                                    <div class="d-inline-block align-middle ml-50">
                                        <div class="share-link">
                                            <span class="font-md-bold color-brand-3 mr-15 d-none d-lg-inline-block">Chia
                                                sẻ</span><a class="facebook hover-up" href="#"></a><a
                                                class="printest hover-up" href="#"></a><a class="twitter hover-up"
                                                href="#"></a><a class="instagram hover-up" href="#"></a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom pt-10 mb-20"></div>
                </div>
                <div class="col-lg-5 gallery-container">
                    {!! $albums !!}
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 mb-30">
                            <div class="box-product-price mb-5">
                                <h3 class="color-brand-3 price-main d-inline-block mr-10">{{ formatMoney($priceDiscount) }}
                                </h3>
                                @if ($discount > 0)
                                    <span
                                        class="color-gray-500 price-line font-xl line-througt">{{ formatMoney($price) }}</span>
                                @endif
                            </div>
                            <div class="product-description color-gray-900 mb-30">
                                {!! $shortContent !!}
                            </div>
                            <div class="mb-xxl-7 mb-2">
                                <strong>Danh mục: </strong>
                                @foreach ($category->where('is_room', 2) as $item)
                                    <a href="{{ route('client.category.index', $item->slug) }}" class="cate_ctsp">
                                        <span class="">
                                            {{ $item->name }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mb-xxl-7 mb-2">
                                <strong>Thương hiệu: </strong>
                                @foreach ($category->where('is_room', 1) as $item)
                                    <a href="{{ route('client.category.index', $item->slug) }}" class="cate_ctsp">
                                        <span class="">
                                            {{ $item->name }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mb-xxl-7 mb-2 status_spct">
                                <strong>Tình trạng: </strong>
                                <span class="">
                                    {{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </span>
                            </div>
                            @include('client.pages.product_detail.components.variant')
                            <div class="buy-product mt-25">
                                <div class="font-sm text-quantity mb-10">Số lượng</div>
                                <div class="box-quantity">
                                    <div class="input-quantity">
                                        <input class="font-xl color-brand-3" name="quantity" type="text" value="1"><span
                                            class="minus-cart"></span><span class="plus-cart"></span>
                                    </div>
                                    <div class="button-buy button-buy-full">
                                        <a class="btn btn-buy addToCart" href="#">Thêm vào giỏ hàng</a>
                                    </div>
                                    <div class="ms-2 mt-1">
                                        <a product-id="{{ $product->id }}" class="mr-20" href="#">
                                            <span class="btn btn-wishlist mr-5 opacity-100 transform-none" 
                                                  data-login="{{ auth()->check() ? 'true' : 'false' }}"></span>
                                        </a>
                                    </div>
                                    <div class="hidden">
                                        <input type="hidden" name="price" id="price" value="{{ $priceDiscount }}">
                                        <input type="hidden" name="sku" id="sku" value="{{ $product->sku }}">
                                        <input type="hidden" name="inventory" class="inventory"
                                            value="{{ $product->quantity }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <div class="pl-30 pl-mb-0">
                                <div class="box-featured-product">
                                    <div class="item-featured">
                                        <div class="featured-icon"><img
                                                src="/client_asset_v1/imgs/page/product/delivery.svg" alt="Ecom"></div>
                                        <div class="featured-info"><span class="font-sm-bold color-gray-1000">
                                            Miễn phí giao hàng
                                        </span>
                                            <p class="font-sm color-gray-500 font-medium">Tất cả đơn hàng trên 1000đ</p>
                                        </div>
                                    </div>
                                    <div class="item-featured">
                                        <div class="featured-icon"><img src="/client_asset_v1/imgs/page/product/support.svg"
                                                alt="Ecom"></div>
                                            <div class="featured-info"><span class="font-sm-bold color-gray-1000">
                                            Hỗ trợ 24/7
                                        </span>
                                            <p class="font-sm color-gray-500 font-medium">Hotline: 0909090909</p>
                                        </div>
                                    </div>
                                    <div class="item-featured">
                                        <div class="featured-icon"><img src="/client_asset_v1/imgs/page/product/return.svg"
                                                alt="Ecom"></div>
                                            <div class="featured-info"><span class="font-sm-bold color-gray-1000">Return &
                                                    Refund</span>
                                            <p class="font-sm color-gray-500 font-medium">Đổi trả trong 7 ngày</p>
                                        </div>
                                    </div>
                                    <div class="item-featured">
                                        <div class="featured-icon"><img src="/client_asset_v1/imgs/page/product/payment.svg"
                                                alt="Ecom"></div>
                                        <div class="featured-info"><span class="font-sm-bold color-gray-1000">
                                            Thanh toán
                                        </span>
                                            <p class="font-sm color-gray-500 font-medium">100% bảo mật</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-sidebar-product">
                                    <div class="banner-right h-500 text-center mb-30 banner-right-product"><span
                                            class="text-no font-11">No.9</span>
                                        <h5 class="font-md-bold mt-10">Sensitive Touch<br class="d-none d-lg-block">without
                                            fingerprint
                                        </h5>
                                        <p class="text-desc font-xs mt-10">Smooth handle and accurate click</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-bottom pt-30 mb-40"></div>
        </div>
    </section>
    <section class="section-box shop-template">
        <div class="container">
            <div class="pt-30 mb-10">
                <ul class="nav nav-tabs nav-tabs-product" role="tablist">
                    <li>
                        <a class="{{ request('tab') != 'danh-gia' ? 'active' : '' }}" href="#tab-description"
                           data-bs-toggle="tab" role="tab" aria-controls="tab-description"
                           aria-selected="{{ request('tab') != 'danh-gia' ? 'true' : 'false' }}">
                            Mô tả
                        </a>
                    </li>
                    <li>
                        <a class="{{ request('tab') == 'danh-gia' ? 'active' : '' }}" href="#tab-reviews"
                           data-bs-toggle="tab" role="tab" aria-controls="tab-reviews"
                           aria-selected="{{ request('tab') == 'danh-gia' ? 'true' : 'false' }}">
                            Đánh giá
                        </a>
                    </li>
                </ul>
            
                <div class="tab-content">
                    <div class="tab-pane fade {{ request('tab') != 'danh-gia' ? 'show active' : '' }}" id="tab-description"
                         role="tabpanel" aria-labelledby="tab-description">
                        <div class="display-text-short">
                            {!! $description !!}
                        </div>
                        <div class="mt-20 text-center">
                            <a class="btn btn-border font-sm-bold pl-80 pr-80 btn-expand-more">Xem thêm</a>
                        </div>
                    </div>
            
                    <div class="tab-pane fade {{ request('tab') == 'danh-gia' ? 'show active' : '' }}" id="tab-reviews"
                         role="tabpanel" aria-labelledby="tab-reviews">
                        <div class="comments-area tab_review_tgnt">
                            {{-- Nội dung đánh giá động sẽ được load ở đây --}}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        </div>
    </section>
    <style>
        .choose-attribute.active {
            background-color: #425A8B;
            color: #fff !important;
        }
    </style>

@endsection