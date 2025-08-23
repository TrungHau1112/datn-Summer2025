@extends('client.layout')

@section('content')
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a class="font-xs color-gray-500" href="{{ route('post.index') }}">Bài viết</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box-filters mt-0 pb-5 border-bottom">
                        <div class="row">
                            <div class="col-xl-2 col-lg-3 mb-0 text-lg-start text-center">
                                <h5 class="color-brand-3 text-uppercase">Bài viết</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-30">
                @foreach ($posts as $post)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-40">
                        <div class="card-grid-style-1">
                            <div class="image-box">
                                <a href="{{ route('client.post.detail', $post->slug) }}">
                                    <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}">
                                </a>
                            </div>
                            <a class="color-gray-1100" href="{{ route('client.post.detail', $post->slug) }}">
                                <h4>{{ $post->title }}</h4>
                            </a>
                            <div class="mt-20">
                                <span class="color-gray-500 font-xs mr-30">{{ $post->created_at->format('d/m/Y') }}</span>
                                <span class="color-gray-500 font-xs">4<span>Phút đọc</span></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-30">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection