@extends('client.layout')
@push('script')
    <script src="/client_asset/custom/js/account.js"></script>
    <script src="/admin_asset/library/location.js"></script>
@endpush
@section('content')
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li><a class="font-xs color-gray-500" href="#">Tài khoản</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="container account mb-5">
        <!-- Account Info -->
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="row">
                    <!-- Hồ sơ -->
                    @include('client.pages.account.components.info')
                </div>
            </div>
            <div class="col-lg-8 col-12">
                <!-- Đơn hàng -->
                @include('client.pages.account.components.order')
            </div>
        </div>
    </section>
@endsection