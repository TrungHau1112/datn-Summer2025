@if (isset($reviewForProduct) && count($reviewForProduct) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="comment-list">
                @foreach ($reviewForProduct as $item)
                    <div class="single-comment justify-content-between d-flex mb-30 hover-up">
                        <div class="user justify-content-between d-flex">
                            <div class="thumb text-center">
                                <img src="{{ $item->user->avatar }}" alt="Avatar">
                            </div>
                            <div class="desc">
                                <div class="d-flex justify-content-between mb-10">
                                    <div class="d-flex flex-column">
                                        <a class="font-heading text-brand" href="#">{{ $item->user->name }}</a>
                                        <span class="font-xs color-gray-700">{{ $item->created_at }}</span>
                                    </div>
                                    <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: {{ ($item->rating / 5) * 100 }}%"></div>
                                    </div>
                                </div>
                                <p class="mb-10 font-sm color-gray-900">
                                    {{ $item->content }}
                                    @if ($item->children && count($item->children) > 0)
                                        @foreach ($item->children as $reply)
                                            <div class="seller-reply ms-3 mt-2 p-2 rounded bg-light">
                                                <h6 class="fw-bold mb-1">Phản hồi của shop</h6>
                                                <p class="reply-text m-0">{{ $reply->content }}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    
        <div class="col-lg-4">
            <div class="d-flex mb-30">
                <div class="product-rate d-inline-block mr-15">
                    <div class="product-rating" style="width: {{ ($rating['average'] / 5) * 100 }}%"></div>
                </div>
                <h6>{{ $rating['average'] }} / 5</h6>
            </div>
            @foreach ($rating['percentages'] as $star => $percent)
                <div class="progress mb-2"><span>{{ $star }} sao</span>
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}"
                        aria-valuemin="0" aria-valuemax="100">
                        {{ $percent }}%
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="col-12">
        <div class="text-center pt-10">
            <img class="mb-3" width="100" src="{{ asset('uploads/image/system/no_product.webp') }}" alt="">
            <p>Chưa có đánh giá nào cho sản phẩm này!</p>
        </div>
    </div>
@endif
