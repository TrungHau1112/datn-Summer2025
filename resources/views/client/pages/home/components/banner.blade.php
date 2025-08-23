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