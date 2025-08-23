@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <form action="{{ route('review.store', $review->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header">
                        Thông tin chung
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <span for="name" class="form-label"><strong>Tên người dùng:</strong> <a
                                            href="#{{ $review->user->name }}">{{ $review->user->name }}</a> </span>
                                </div>
                                <div class="mb-2">
                                    <span for="name" class="form-label"><strong>Sản phẩm:</strong> <a
                                            href="{{ route('client.product.detail', $review->product->slug) }}">{{ $review->product->name }}</a>
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <span for="name" class="form-label"><strong>Ngày đánh giá:</strong>
                                        {{ changeDateFormat($review->created_at) }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Đánh giá:</strong>
                                    {{-- <div class="review-rating"> --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <span class="star filled text-warning"><i class="fas fa-star"></i></span>
                                        @else
                                            <span class="star text-warning"><i class="far fa-star"></i></span>
                                        @endif
                                    @endfor
                                    {{-- </div> --}}
                                </div>
                                <div class="mb-2">
                                    <strong>Nội dung:</strong>
                                    <span class="row-name">{{ $review->content }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Trả lời:</strong>
                                    <textarea placeholder="Phản hồi đánh giá của khách hàng" class="form-control" name="content" id=""
                                        cols="10" rows="3">{{ old('content', $review->children->first()->content ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-3">
                <x-save_back :model="$config['model']" />
            </div>


        </div>
        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}" />
    </form>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
