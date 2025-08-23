<style>
.price-main {
    background: linear-gradient(90deg, #fa0004, #ff4d00);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 24px;
    font-weight: 900;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.25);
}


</style>
<section class="section-box mt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="head-main">
                    <h3 class="mb-5">Sản phẩm nổi bật</h3>
                    <p class="font-base color-gray-500">Sản phẩm nổi bật nhất trong tháng.</p>
                    <div class="box-button-slider">
                        <div class="swiper-button-next swiper-button-next-group-1"></div>
                        <div class="swiper-button-prev swiper-button-prev-group-1"></div>
                    </div>
                </div>
                <div class="box-swiper">
                    <div class="swiper-container swiper-group-1">
                        <div class="swiper-wrapper pt-5">
                            <div class="swiper-slide">
                                <div class="row">
                                    @foreach ($product_featureds_1 as $item)
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="card-grid-style-2">
                                                <div class="image-box"><a
                                                        href="{{ route('client.product.detail', $item->slug) }}"><img
                                                            src="{{ $item->thumbnail }}" alt="Ecom"></a></div>
                                                <div class="info-right"><span class="font-xs color-gray-500">#</span><br><a
                                                        class="color-brand-3 font-sm-bold"
                                                        href="{{ route('client.product.detail', $item->slug) }}">{{ $item->name }}</a>
                                                    <div class="price-info"><strong
                                                            class="font-lg-bold color-brand-3 price-main">{{ formatMoney($item->price - ($item->price * $item->discount) / 100) }}</strong>
                                                        @if ($item->discount > 0)
                                                            <span
                                                                class="color-gray-500 price-line">{{ formatMoney($item->price) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row">
                                    @foreach ($product_featureds_2 as $item)
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="card-grid-style-2">
                                                <div class="image-box"><a
                                                        href="{{ route('client.product.detail', $item->slug) }}"><img
                                                            src="{{ $item->thumbnail }}" alt="Ecom"></a></div>
                                                <div class="info-right"><span class="font-xs color-gray-500">#</span><br><a
                                                        class="color-brand-3 font-sm-bold"
                                                        href="{{ route('client.product.detail', $item->slug) }}">{{ $item->name }}</a>
                                                    <div class="price-info"><strong
                                                            class="font-lg-bold color-brand-3 price-main">{{ formatMoney($item->price - ($item->price * $item->discount) / 100) }}</strong>
                                                        @if ($item->discount > 0)
                                                            <span
                                                                class="color-gray-500 price-line">{{ formatMoney($item->price) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="banner-right text-center"><span class="text-no">Số 09</span>
                    <h5 class="text-main mt-20">Cảm ứng nhạy<br class="d-none d-lg-block">không có dấu vân tay
                    </h5>
                    <p class="text-desc mt-15">Tay cầm trơn tru và nhấp chuột chính xác</p>
                </div>
            </div>
        </div>
    </div>
</section>