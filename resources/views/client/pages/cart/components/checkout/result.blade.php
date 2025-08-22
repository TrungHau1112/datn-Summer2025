@extends('client.layout')

@section('content')
<div class="section-box">
    <div class="breadcrumbs-div">
        <div class="container">
            <ul class="breadcrumb">
                <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                <li><a class="font-xs color-gray-500" href="#">Kết quả thanh toán</a></li>
            </ul>
        </div>
    </div>
</div>
    <section class="container d-flex justify-content-center p-10 py-15">
        <div id="paymentResult text-center" class="mb-4">
            <div class="icon-success text-center text-success {{ $status === 'success' ? '' : 'd-none' }}">
                <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
            </div>
            <div class="icon-failure text-center text-danger {{ $status === 'error' ? '' : 'd-none' }}">
                <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
            </div>
            <h4 id="message" class="mt-3 text-center">{{ $message }}</h4>
            <div class="mt-4 d-flex justify-content-center gap-5">
                <a href="{{ route('client.home') }}" class="btn btn-outline-tgnt btn-sm">Quay về trang chủ</a>
                @if ($status === 'error')
                    <form action="{{ $payment_method == 'momo' ? route('client.checkout.momo.pay-again') : route('client.checkout.vnpay.pay-again') }}" method="POST">
                        @csrf
                        <input type="hidden" name="code" value="{{ $code }}">
                        <button type="submit" class="btn btn-tgnt btn-sm">Thanh toán lại</button>
                    </form>
                    {{-- <a href="{{ route('client.checkout.vnpay.pay') }}" class="btn btn-tgnt btn-sm">Thanh toán lại</a> --}}
                @endif
            </div>
        </div>
    </section>
@endsection
