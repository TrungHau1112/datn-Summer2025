@extends('client.layout')
@section('seo')
    <title>{{ $post->title }}</title>
    <meta name="description" content="{{ $post->description }}">
@endsection
@section('content')

    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a class="font-xs color-gray-500" href="{{ route('post.index') }}">Bài viết</a></li>
                    <li><a class="font-xs color-gray-500"
                            href="{{ route('client.post.detail', $post->slug) }}">{{ $post->title }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template mt-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-12 mb-50 display-list"><a class="tag-dot font-xs" href="#">#</a>
                            <h3 class="mt-15 mb-25">{{ $post->title }}</h3>
                            <div class="box-author mb-5">
                                <div class="img-author mr-30"><img src="{{ asset($post->user->avatar) }}" alt="Ecom"><span
                                        class="font-md-bold">{{ $post->user->name }}</span></div><span
                                    class="datepost color-gray-500 font-sm mr-30">{{ $post->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="image-feature"><img src="{{ asset($post->thumbnail) }}" alt="Ecom"></div>
                            <div class="content-text">
                                {!! $post->content !!}
                            </div>
                            <div class="border-bottom-4 mb-20"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-box shop-template mt-30">
        <div class="container">
            <h3 class="color-brand-3">Bài viết liên quan</h3>
            <div class="row mt-30">
                @foreach ($postRelateds as $postRelated)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-40">
                        <div class="card-grid-style-1">
                            <div class="image-box">
                                <a href="{{ route('client.post.detail', $postRelated->slug) }}">
                                    <img src="{{ asset($postRelated->thumbnail) }}" alt="Ecom"></a>
                            </div>
                            <a class="color-gray-1100" href="{{ route('client.post.detail', $postRelated->slug) }}">
                                <h4 class="mb-10">{{ $postRelated->title }}</h4>
                            </a>
                            <div class="row mt-20">
                                <div class="col-12"><span
                                        class="color-gray-500 font-xs mr-30">{{ $postRelated->created_at->format('d/m/Y') }}</span><span
                                        class="color-gray-500 font-xs">4<span> Phút đọc</span></span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection