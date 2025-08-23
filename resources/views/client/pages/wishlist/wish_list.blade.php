@extends('client.layout')

@section('content')
<div class="section-box">
    <div class="breadcrumbs-div">
        <div class="container">
            <ul class="breadcrumb">
                <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                <li><a class="font-xs color-gray-500" href="#">Yêu thích</a></li>
            </ul>
        </div>
    </div>
</div>
    <section class="container my-3">
        <div data-url-home="{{ route('client.home') }}" class="row animate__animated animate__fadeIn listProduct mb-4">
            @if (Auth()->check())
                @if ($user->wishlists->count() > 0)
                <div class="list-products-5">
                    @foreach ($user->wishlists as $wishlist)
                        <x-product_card :data="$wishlist->product" :dataType="'remove'" />
                    @endforeach
                </div>
                @else
                    <div class="col-12 py-15 animate__animated animate__fadeIn">
                        <div class="text-center">
                            <img class="mb-3 mb-3" width="100"
                                src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/orderlist/5fafbb923393b712b964.png"
                                alt="">
                            <p>Chưa có sản phẩm yêu thích nào!</p>
                            <a class="btn btn-tgnt mt-3" href="{{ route('client.home') }}">Thêm ngay</a>
                        </div>
                    </div>
                @endif
            @else
                <div class="col-12 py-15 animate__animated animate__fadeIn">
                    <div class="text-center">
                        <img class="mb-3 mb-3" width="100"
                            src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/orderlist/5fafbb923393b712b964.png"
                            alt="">
                        <p class="text-tgnt">Bạn cần phải đăng nhập, để xem được sản phẩm yêu thích của mình !</p>
                        <a class="btn btn-tgnt" href="{{ route('client.auth.login') }}">Đăng nhập ngay</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection