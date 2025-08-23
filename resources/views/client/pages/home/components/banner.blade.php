<section class="section-box">
    <div class="banner-hero banner-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="box-swiper">
                        <div class="swiper-container swiper-group-1">
                            <div class="swiper-wrapper">
                                @foreach ($slides as $slide)
                                <div class="swiper-slide">
                                    <div class="banner-big bg-11"
                                        style="background-image: url({{ $slide->image }})">
                                        <span class="font-sm text-uppercase">Khuyến mãi hot</span>
                                        <h2 class="mt-10">Giảm giá lên đến 50%</h2>
                                        <h1>Thiết bị di động</h1>
                                        <div class="row">
                                            <div class="col-lg-5 col-md-7 col-sm-12">
                                                <p class="font-sm color-brand-3">Khám phá ngay bộ sưu tập điện thoại mới nhất với nhiều ưu đãi hấp dẫn. 
                                                Chúng tôi cam kết mang đến cho bạn những sản phẩm chất lượng với giá tốt nhất thị trường.</p>
                                            </div>
                                        </div>
                                        <div class="mt-30"><a class="btn btn-brand-2" href="{{ route('client.product.index') }}">
                                            Mua ngay
                                        </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination swiper-pagination-1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="banner-small banner-small-1 bg-13">
                                <span class="color-danger text-uppercase font-sm-lh32">10%<span class="color-brand-3">Giảm giá</span></span>
                                <h4 class="mb-10">Apple Watch Series 7</h4>
                                <p class="color-brand-3 font-desc">Đừng bỏ lỡ cơ hội<br class="d-none d-lg-block"> cuối cùng này.</p>
                                <div class="mt-20">
                                    <a class="btn btn-brand-3 btn-arrow-right" href="{{ route('client.product.index') }}">Mua ngay</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="banner-small banner-small-2 bg-14">
                                <span class="color-danger text-uppercase font-sm-lh32">BỘ SƯU TẬP MỚI</span>
                                <h4 class="mb-10">Thiết bị & Phần mềm Apple</h4>
                                <p class="color-brand-3 font-md">Đừng bỏ lỡ cơ hội<br class="d-none d-lg-block"> cuối cùng này.</p>
                                <div class="mt-20">
                                    <a class="btn btn-brand-2 btn-arrow-right" href="{{ route('client.product.index') }}">Mua ngay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- FLASH SALE SECTION --}}
<section class="section-box pt-50">
    <div class="container">
        <div class="head-main">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <h3 class="mb-5">🔥 Flash Sale hôm nay</h3>
                    <p class="font-base color-gray-500">Nhanh tay chốt đơn – Săn deal cực sốc!</p>
                </div>
            </div>
        </div>

        <!-- FLASH SALE CARD -->
        <section id="flash-sale" class="card"
            data-start="2025-08-20T00:00:00+07:00"
            data-end="2025-08-22T23:59:59+07:00"
            data-now-locale="vi-VN">

            <div class="ribbon">FLASH • SALE</div>
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h4 class="mb-0">Đồng hồ đếm ngược</h4>
                <span id="fs-status" class="pill soon">Sắp diễn ra</span>
            </div>
            <p class="font-base color-gray-500 mb-3">Thời gian còn lại để chốt đơn:</p>

            <!-- Countdown -->
            <div class="countdown d-flex gap-2 mb-3">
                <div class="slot text-center flex-fill">
                    <div class="num" id="d">00</div>
                    <div class="lbl">Ngày</div>
                </div>
                <div class="slot text-center flex-fill">
                    <div class="num" id="h">00</div>
                    <div class="lbl">Giờ</div>
                </div>
                <div class="slot text-center flex-fill">
                    <div class="num" id="m">00</div>
                    <div class="lbl">Phút</div>
                </div>
                <div class="slot text-center flex-fill">
                    <div class="num" id="s">00</div>
                    <div class="lbl">Giây</div>
                </div>
            </div>

            <!-- Thanh tiến độ -->
            <div class="progress-wrap">
                <div class="progress"><div class="bar" id="bar"></div></div>
                <div class="progress-label d-flex justify-content-between">
                    <span>Bắt đầu: <strong id="startLabel">--</strong></span>
                    <span>Kết thúc: <strong id="endLabel">--</strong></span>
                </div>
            </div>
        </section>
    </div>
</section>


