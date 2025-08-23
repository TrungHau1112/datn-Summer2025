{{-- @extends('client.layout')

@section('content')
    <section class="container ">
        <div class="d-none d-xxl-block mp-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-stnt">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">So sánh sản phẩm</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row rounded p-3 mb-4" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                <div class="col-md-12 col-12 fs-5 text-center">
                    <h3 class="fw-bold">Sản phẩm:</h3>
                    @foreach ($products as $key => $product)
                        <p class="fw-bold">{{ $product->product->name ?? $product->name }}
                            @if (!empty($product->title))
                                ({{ $product->title ?? '' }})
                            @endif
                        </p>
                        @if ($key == 0)
                            <p class="fw-bold">&</p>
                        @endif
                    @endforeach
                </div>
                @foreach ($products as $product)
                    <div class="col-md-6 col-6 border border-1">
                        <div class="product_item position-relative text-center mb-3">
                            <img class="Sirv image-main" src="{{ $product->product->thumbnail ?? $product->thumbnail }}"
                                data-src="{{ $product->product->thumbnail ?? $product->thumbnail }}" alt=""
                                width="100%">
                            <div class="p-3">
                                <p class="fs-6" style="">{{ $product->product->name ?? $product->name }}
                                    @if (!empty($product->title))
                                        ({{ $product->title ?? '' }})
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row rounded p-3 mb-4" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                <div class="col-md-12 col-12  fs-5">
                    <h3 class="fw-bold p-3 m-0">Thông tin sản phẩm</h3>
                </div>
                @foreach ($products as $product)
                    <div class="col-md-6 col-6 border border-1">
                        <div class="product_item position-relative mb-3">
                            <div class="p-3">
                                <p class="fw-bold m-0 fs-4">Mô tả:</p>
                                <p class="fs-6" style="">
                                    {{ $product->product->short_content ?? $product->short_content }}</p>
                                <div class="text-tgnt">
                                    @if ($product->product->discount ?? $product->discount > 0)
                                        <p class="fw-bold">Giá gốc: <span
                                                class="text-decoration-line-through text-muted">{{ formatMoney($product->price) }}đ</span>
                                        </p>
                                        <p class="fw-bold">Giảm giá: <span
                                                class="badge bg-light-warning text-dark-warning">-{{ $product->product->discount ?? $product->discount }}%</span>
                                        </p>
                                    @endif
                                    <p class="fs-5 fw-bold">
                                        Giá hiện tại:
                                        <span>{{ formatMoney($product->price - ($product->price * ($product->product->discount ?? $product->discount)) / 100) }}đ</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row rounded p-3 mb-4" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                <div class="col-md-12 col-12  fs-5">
                    <h3 class="fw-bold p-3 m-0">Mô tả chi tiết</h3>
                </div>
                @foreach ($products as $product)
                    <div class="col-md-6 col-6 border border-1 ">
                        <div class="product_item position-relative mb-3 ">
                            <div class="p-3">
                                <p class="fs-6 compare-detail-description" style="">
                                    {!! $product->product->description ?? $product->description !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <style> img {
        width: 100% !important;
    }</style>
@endsection --}}
