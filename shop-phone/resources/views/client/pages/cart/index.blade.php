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
                        <p class="fs-6 my-3">Bạn đang có <span class="fw-bold cart_count">{{ $carts->count() }}</span> sản phẩm trong giỏ hàng</p>
                        <div class="list-cart line-y" id="list-cart">
                            {!! $listCart !!}
                        </div>
                    </div>
                </div>

                <div class="main-right col-xxl-4 col-md-12 col-12 border rounded h-100">
                    <p class="fs-2 text-dark py-4 fw-bold">Tổng quan đơn hàng</p>
                    <div class="d-flex justify-content-between">
                        <p class="fs-6">Giỏ hàng (<span class="cart_count">{{ $carts->count() }}</span> sản phẩm):</p>
                        <p class="cart-total"><span id="cart-total">{{ formatMoney($totalCart) }}</span>₫</p>
                        <input type="hidden" id="cart-total-input" value="{{ $totalCart }}">
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="fs-6">Thành tiền:</p>
                        <p class="cart-total"><span id="cart-total-discount-collection">{{ formatMoney($totalCart) }}</span>₫</p>
                        <input type="hidden" id="cart-total-discount-collection-input" value="{{ $totalCart }}">
                    </div>

                    <p class="cart-discount-title mt-3">Thông tin giao hàng</p>
                    <p class="my-2 text">Đối với những sản phẩm có sẵn tại khu vực, "{{ getSetting()->site_name }}" sẽ giao hàng trong vòng 2-7 ngày. Đối với những sản phẩm không có sẵn, thời gian giao hàng sẽ được nhân viên "{{ getSetting()->site_name }}" thông báo đến quý khách.</p>

                    <div class="cart-last-step d-flex my-4">
                        <a href="{{ route('client.home') }}" class="cart-back btn btn-outline-tgnt w-50 ms-2">Tiếp tục mua hàng</a>
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

<!-- Modal cảnh báo mua hàng lớn hơn 20 triệu -->
<div class="modal fade" id="bigOrderAlert" tabindex="-1" aria-labelledby="bigOrderAlertLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="bigOrderAlertLabel">⚠️ Cảnh báo đơn hàng lớn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        Đơn hàng của bạn có tổng giá trị lớn hơn <strong>20.000.000 VNĐ</strong>. Bạn có chắc chắn muốn tiếp tục không?
      </div>
      <div class="modal-footer">
        <a href="{{ $carts->count() > 0 ? route('client.checkout.index') : 'javascript:void(0);' }}"
   class="checkout-cart btn w-50 ms-2"
   style="background-color: #28a745; color: white;">
   Tiếp tục đặt hàng
</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
      </div>
    </div>
  </div>
</div>

<!-- Script xử lý cảnh báo -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkoutBtn = document.querySelector(".checkout-cart");

        if (checkoutBtn) {
            checkoutBtn.addEventListener("click", function (e) {
                e.preventDefault();

                const totalInput = document.getElementById("cart-total-input");
                if (!totalInput) return;

                const total = parseInt(totalInput.value.replace(/\D/g, ''));

                if (total > 20000000) {
                    const modal = new bootstrap.Modal(document.getElementById("bigOrderAlert"));
                    modal.show();
                } else {
                    window.location.href = checkoutBtn.getAttribute("href");
                }
            });
        }
    });
</script>

@endsection
