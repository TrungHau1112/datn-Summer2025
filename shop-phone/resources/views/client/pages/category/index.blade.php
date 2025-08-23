@extends('client.layout')
@section('seo')
    @include('client.components.seo')
@endsection
@section('content')
    <input type="hidden" name="url_getProduct" value="{{ route('client.category.get-product') }}">
    <section class="container mt-2">
        <div class="col-xxl-12 d-none d-xxl-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-stnt">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item"><a href="#" class="text-stnt">Danh mục</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <section class="container bestsell-categories mt-6">
            <div class="mt-4">
                @include('client.pages.category.components.filter')
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="product_container show">
                    @include('client.pages.category.components.product')
                </div>
            </div>
        </section>
    </section>

    <!--  -->
    <!--  -->
    {{-- <section class="export_sesstion container mb-5 mt-5">
        <div class="explore-heading d-flex">
            <h1>Khám phá thêm</h1>
            <hr />
        </div>
        <div class="row">
            <div class="col-md-3 mb-3 card_img_export">
                <img width="100%" src="/assets/image/footer/Container1.png" alt="" />
                <div class="py-3 d-flex gap-3 align-items-center">
                    <a href="" class="text-dark text-decoration-none">Woven decor</a>
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3 card_img_export">
                <img width="100%" src="/assets/image/footer/Container.png" alt="" />
                <div class="py-3 d-flex gap-3 align-items-center">
                    <a href="" class="text-dark text-decoration-none">Kids lighting</a>
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3 card_img_export">
                <img width="100%" src="/assets/image/footer/Container2.png" alt="" />
                <div class="py-3 d-flex gap-3 align-items-center">
                    <a href="" class="text-dark text-decoration-none">Xmas decor</a>
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3 card_img_export">
                <img width="100%" src="/assets/image/footer/Container3.png" alt="" />
                <div class="py-3 d-flex gap-3 align-items-center">
                    <a href="" class="text-dark text-decoration-none">Halloween decor</a>
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </section> --}}
    <!--  -->

    <!-- slide -->
    {{-- <section class="recently_vd container mb-5">
        <div>
            <h3>Đã xem gần đây</h3>
            <button class="carousel-control-next" type="button" data-bs-target="#recentlyViewedCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"><i
                        class="fa-solid fa-chevron-right"></i></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div id="recentlyViewedCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img class="img-fluid" data-img-hover="/assets/image/footer/pic1.png"
                                            src="/assets/image/footer/pic.png" class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img class="img-fluid" src="/assets/image/footer/pic.png"
                                            class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img class="img-fluid" src="/assets/image/footer/pic.png"
                                            class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img class="img-fluid" src="/assets/image/footer/pic.png"
                                            class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img src="/assets/image/footer/pic.png" class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img src="/assets/image/footer/pic.png" class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img src="/assets/image/footer/pic.png" class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                            <div class="product-card">
                                <a href="">
                                    <div>
                                        <img src="/assets/image/footer/pic.png" class="product-image img-fluid" />
                                    </div>
                                </a>
                                <a href="" class="title_product">
                                    <h6 class="mt-2">Gather Couch</h6>
                                </a>
                                <p>12,300,000 đ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev d-none" type="button" data-bs-target="#recentlyViewedCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"><i
                        class="fa-solid fa-chevron-left"></i></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next d-none" type="button" data-bs-target="#recentlyViewedCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"><i
                        class="fa-solid fa-chevron-right"></i></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section> --}}
    <!-- end slide -->
@endsection
