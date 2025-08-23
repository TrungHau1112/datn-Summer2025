@extends('client.layout')

@section('content')
    @include('client.pages.home.components.banner')
    @include('client.pages.home.components.category')
    @include('client.pages.home.components.sellers')
    @include('client.pages.home.components.selling')
    @include('client.pages.home.components.news')
    <section class="section-box mt-90 mb-50">
        <div class="container">
            <ul class="list-col-5">
                <li>
                    <div class="item-list">
                        <div class="icon-left"><img src="/client_asset_v1/imgs/template/delivery.svg" alt="Ecom"></div>
                        <div class="info-right">
                            <h5 class="font-lg-bold color-gray-100">Free Delivery</h5>
                            <p class="font-sm color-gray-500">From all orders over $10</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-list">
                        <div class="icon-left"><img src="/client_asset_v1/imgs/template/support.svg" alt="Ecom"></div>
                        <div class="info-right">
                            <h5 class="font-lg-bold color-gray-100">Support 24/7</h5>
                            <p class="font-sm color-gray-500">Shop with an expert</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-list">
                        <div class="icon-left"><img src="/client_asset_v1/imgs/template/voucher.svg" alt="Ecom"></div>
                        <div class="info-right">
                            <h5 class="font-lg-bold color-gray-100">Gift voucher</h5>
                            <p class="font-sm color-gray-500">Refer a friend</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-list">
                        <div class="icon-left"><img src="/client_asset_v1/imgs/template/return.svg" alt="Ecom"></div>
                        <div class="info-right">
                            <h5 class="font-lg-bold color-gray-100">Return &amp; Refund</h5>
                            <p class="font-sm color-gray-500">Free return over $200</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-list">
                        <div class="icon-left"><img src="/client_asset_v1/imgs/template/secure.svg" alt="Ecom"></div>
                        <div class="info-right">
                            <h5 class="font-lg-bold color-gray-100">Secure payment</h5>
                            <p class="font-sm color-gray-500">100% Protected</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <section class="section-box box-newsletter">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7 col-sm-12">
                    <h3 class="color-white">Subscrible &amp; Get <span class="color-warning">10%</span> Discount</h3>
                    <p class="font-lg color-white">Get E-mail updates about our latest shop and <span
                            class="font-lg-bold">special offers.</span></p>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-12">
                    <div class="box-form-newsletter mt-15">
                        <form class="form-newsletter">
                            <input class="input-newsletter font-xs" value="" placeholder="Your email Address">
                            <button class="btn btn-brand-2">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
