@props(['data', 'dataType', 'class'])
<div class="product_item col-md-6 col-lg-4 col-xl-3 text-center mb-3">
    <a href="{{ route('client.product.detail', $data->slug) }}" class="item position-relative">
        <img class="Sirv image-main" src="{{ $data->thumbnail }}" data-src="{{ $data->thumbnail }}" alt="">
        <img class="Sirv image-hover" src="{{ $data->albums != [] ? json_decode($data->albums)[0] : '' }}" alt="">
        <div class="p-3">
            <h2 style="">{{ $data->name }}</h2>
            <div class="price text-tgnt">{{ formatMoney($data->price - ($data->price * $data->discount) / 100 ) }}</div>
        </div>

        {{-- discount --}}
        @if ($data->discount > 0)
            <div class="position-absolute top-0 start-0 mt-4 ms-4" style="z-index: 10">
                <h5><span class="badge bg-light-warning text-dark-warning">- {{ $data->discount }}%</span></h5>
            </div>
        @endif
        {{-- tym --}}
        <label for="like{{ $data->id }}" style="cursor: pointer; z-index:10;"
            title="Thêm sản phẩm vào mục yêu thích"
            class="animate__animated animate__bounceIn like_action con-like position-absolute top-0 end-0 mt-4 me-4">
            <input data-type="{{ $dataType ?? '' }}"
                {{ auth()->check() &&auth()->user()->wishlists->contains('product_id', $data->id)? 'checked': '' }}
                class="like action_wishlist" data-login="{{ auth()->check() ? 'true' : 'false' }}"
                id="like{{ $data->id }}" value="{{ $data->id }}" data-id="{{ $data->id }}"
                type="checkbox">
            <div class="checkmark">
                <svg xmlns="http://www.w3.org/2000/svg" class="outline" viewBox="0 0 24 24">
                    <path
                        d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z">
                    </path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="filled" viewBox="0 0 24 24">
                    <path
                        d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z">
                    </path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" height="100" width="100" class="celebrate">
                    <polygon class="poly" points="10,10 20,20"></polygon>
                    <polygon class="poly" points="10,50 20,50"></polygon>
                    <polygon class="poly" points="20,80 30,70"></polygon>
                    <polygon class="poly" points="90,10 80,20"></polygon>
                    <polygon class="poly" points="90,50 80,50"></polygon>
                    <polygon class="poly" points="80,80 70,70"></polygon>
                </svg>
            </div>
        </label>
    </a>
</div>
