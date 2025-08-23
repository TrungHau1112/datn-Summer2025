@props(['data', 'dataType', 'class'])
<div class="card-grid-style-3">
    <div class="card-grid-inner">
        <div class="tools">
            <a product-id="{{ $data->id }}" data-type="{{ $dataType }}" class="add_to_wishlist btn btn-wishlist btn-tooltip mb-10" href="#" aria-label="Yêu thích"></a>
        </div>
        <div class="image-box">
            @if ($data->discount > 0)
                <span class="label bg-brand-2">-{{ $data->discount }}%</span>
            @endif
            <a href="{{ route('client.product.detail', $data->slug) }}"><img src="{{ $data->thumbnail }}"
                    alt="Ecom"></a>
        </div>
        <div class="info-right"><a class="font-xs color-gray-500" href="#danhmuc"></a><br><a
                class="color-brand-3 font-sm-bold"
                href="{{ route('client.product.detail', $data->slug) }}">{{ $data->name }}</a>
            <div class="price-info"><strong
                    class="font-lg-bold color-brand-3 price-main">{{ formatMoney($data->price - ($data->price * $data->discount) / 100) }}</strong>
                @if ($data->discount > 0)
                    <span class="color-gray-500 price-line">
                        {{ formatMoney($data->price) }}
                    </span>
                @endif
            </div>
            <div class="mt-20 box-btn-cart"><a class="btn btn-cart"
                    href="{{ route('client.product.detail', $data->slug) }}">Xem chi tiết</a></div>
        </div>
    </div>
</div>