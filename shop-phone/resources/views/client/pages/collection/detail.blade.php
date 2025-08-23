@extends('client.layout')
@section('seo')
    @include('client.components.seo')
@endsection
@section('content')
    <main>
        <section class="container collection_tgnt" data-slug="{{ $collection->slug }}">
            <div class="d-none d-xxl-block mp-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-stnt">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('client.collection.index') }}" class="text-stnt">Bộ sưu
                                tập</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $collection->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="container detail">
                <div class="banner mb-3">
                    <img class="banner-image rounded" src="{{ $collection->thumbnail }}" alt="">
                    <p class="banner-caption">{{ $collection->name }}</p>
                    <p class="banner-description">{{ $collection->short_description }}</p>
                </div>
                <div class="content">
                    {!! $collection->description_text !!}
                </div>
                <div class="product-of-collection">
                    <p class="title">Sản phẩm thuộc bộ sưu tập</p>
                    {!! $collection->description !!}
                    <div class="text-center my-3">

                        <button type="button" class="btn btn-tgnt getCollection" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Thêm bộ sưu tập vào giỏ hàng
                        </button>
                    </div>
                </div>
                @include('client.pages.collection.components.modal_product')
                <div class="row">
                        <div class="comment-collection col-12 col-md-9">
                            {{-- RENDER JS --}}
                        </div>
                    <div class="col-md-3 col-12">
                        <div class="bg-light p-5 rounded mb-5">
                            <h5 class="mt-1 fw-bold">Xem các bộ sưu tập khác</h5>
                            <hr class="border-top border-3 w-25 my-2">
                            <div class="row">
                                @foreach ($collections as $collection)
                                    <div class="col-6 col-md-12 mb-2">
                                        <a href="">{{ $collection->name }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </section>
    </main>
    {{-- <div id="fb-root"></div> --}}
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v21.0&appId=458546473274979"></script>
@endsection
