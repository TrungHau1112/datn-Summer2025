@extends('client.layout')
@section('content')
    <input type="hidden" name="url_getProduct" value="{{ route('client.category.get-product') }}">
    <section class="container">
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
                <div class="product_container">
                    @include('client.pages.category.components.product')
                </div>
            </div>
        </section>
    </section>
@endsection
