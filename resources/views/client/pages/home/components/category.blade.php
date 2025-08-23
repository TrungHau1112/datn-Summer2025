<section class="section-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <h3>Danh mục nổi bật</h3>
                <p class="font-base">Chọn sản phẩm cần thiết của bạn từ danh mục tính năng này.</p>
            </div>
            <div class="col-lg-7">
                <div class="list-brands">
                    <div class="box-swiper">
                        <div class="swiper-container swiper-group-7">
                            <div class="swiper-wrapper">
                                @if (isset($brands) && $brands->count() > 0)
                                    @foreach ($brands as $brand)
                                        <div class="swiper-slide"><a href="{{'/san-pham?brand_id=' . $brand->id}}"><img
                                                    src="{{$brand->thumbnail}}" alt="Logo brand"></a></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-50">
            <div class="row">
                @if (isset($categories) && $categories->count() > 0)
                    @foreach ($categories as $category)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card-grid-style-2 card-grid-style-2-small hover-up">
                                <div class="image-box">
                                    <a href="{{'/san-pham?category_id=' . $category->id}}">
                                        <img src="{{$category->thumbnail}}" alt="Ecom">
                                    </a>
                                </div>
                                <div class="info-right">
                                    <a class="color-brand-3 font-sm-bold" href="{{'/san-pham?category_id=' . $category->id}}">
                                        <h6>{{$category->name}}</h6>
                                    </a>
                                    <ul class="list-links-disc">
                                        @foreach ($category->children->take(4) as $children)
                                            <li><a class="font-sm" href="{{'/san-pham?category_id=' . $children->id}}">{{$children->name}}</a></li>
                                        @endforeach
                                    </ul>
                                    <a class="btn btn-gray-abs" href="{{'/san-pham?category_id=' . $category->id}}">Xem Tất Cả</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif


            </div>
        </div>
    </div>
</section>