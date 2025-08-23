<section class="section-box mt-50">
    <div class="container">
        <div class="head-main">
            <h3 class="mb-5">Bài viết mới nhất</h3>
            <p class="font-base color-gray-500">From our blog, forum</p>
            <div class="box-button-slider">
                <div class="swiper-button-next swiper-button-next-group-4"></div>
                <div class="swiper-button-prev swiper-button-prev-group-4"></div>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <div class="box-swiper">
            <div class="swiper-container swiper-group-4">
                <div class="swiper-wrapper pt-5">
                    @foreach ($posts as $item)
                        <div class="swiper-slide">
                            <div class="card-grid-style-1">
                                <div class="image-box"><a href="blog-single-2.html"></a><img
                                        src="{{ $item->thumbnail }}" alt="Ecom"></div><a class="color-gray-1100"
                                    href="blog-single-2.html">
                                    <h4>{{ $item->title }}</h4>
                                </a>
                                <div class="mt-20"><span class="color-gray-500 font-xs mr-30">
                                        {{ $item->created_at->format('d/m/Y') }}</span>
                                    </span></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
