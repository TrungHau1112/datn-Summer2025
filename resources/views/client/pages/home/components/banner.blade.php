<section class="section-box">
    <div class="banner-hero banner-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="hero">
                        <div class="swiper-container swiper-group-1">
                            <div class="swiper-wrapper">
                                @foreach ($slides as $slide)
                                <div class="swiper-slide">
                                    <div class="banner-big bg-11"
                                        style="background-image: url({{ $slide->image }})">
                                        <h2>Khuyến mãi hot</h2>
                                        <h1>Thiết bị di động</h1>
                                        <div class="row">
                                            <div class="col-lg-5 col-md-7 col-sm-12">
                                                <p class="font-sm color-brand-3">Khám phá ngay bộ sưu tập điện thoại mới nhất với nhiều ưu đãi hấp dẫn. 
                                                Chúng tôi cam kết mang đến cho bạn những sản phẩm chất lượng với giá tốt nhất thị trường.</p>
                                            </div>
                                        </div>
                                        <div class="mt-10"><a class="btn-primary-ui" href="{{ route('client.product.index') }}">Mua ngay
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-left:8px;vertical-align:-2px;">
                                                <path d="M13.5 4.5a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6A1 1 0 0 1 13.5 16.5L17.086 13H4a1 1 0 1 1 0-2h13.086L13.5 7.5a1 1 0 0 1 0-1.414Z"/>
                                            </svg>
                                        </a></div>
                                        <div class="trust-bar">
                                            <div class="trust-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm-1 15-5-5 1.414-1.414L11 13.172l6.586-6.586L19 8z"/></svg>
                                                <span class="badge-ui">Chính hãng</span> <strong>100%</strong>
                                            </div>
                                            <div class="trust-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22a1 1 0 0 1-1-1v-3.055A7.002 7.002 0 0 1 5 11V7a7 7 0 0 1 14 0v4a7.002 7.002 0 0 1-6 6.945V21a1 1 0 0 1-1 1Z"/></svg>
                                                <span class="badge-ui">Đổi trả</span> <strong>30 ngày</strong>
                                            </div>
                                            <div class="trust-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6a1 1 0 0 1 1-1h11l6 6v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm13 1v4h4Z"/></svg>
                                                <span class="badge-ui">Giao nhanh</span> <strong>2–5 ngày</strong>
                                            </div>
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