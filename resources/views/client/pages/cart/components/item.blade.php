<div class="cart-item" id="cart-item-{{ $product->idCart }}"
    data-productSku="{{ $product->product->sku ?? $product->sku }}" data-id="{{ $product->idCart }}"
    style="z-index: {{ $zIndex }};">
    <img id="thumbnail-{{ $product->idCart }}" alt="Black and white striped ceramic plate" height="100"
        src="{{ $product->thumbnail }}" width="100" />
    <div class="cart-item-details">
        <div class="cart-item-title">
            <a
                href="{{ route('client.product.detail', $product->slug ?? $product->product->slug) }}">{{ $product->name }}</a>
        </div>
        <div class="d-xxl-flex">
                <input type="hidden" class="price" id="origin-price-{{ $product->idCart }}-input"
                    value="{{ $product->price - ((int)$product->price * (int)$product->discount) / 100 }}">
                </input>
                <div class="cart-item-price">
                    Giá: <span class="price" id="price-{{ $product->sku }}">{{ $product->price - ((int)$product->price * (int)$product->discount) / 100  }}</span>₫
                </div>
        </div>
    </div>
    <div class="cart-item-subtitle">
        @if (isset($product->title))
            <button class="open-box-variant text-muted btn btn-link p-0" data-idCart="{{ $product->idCart }}">
                Phân loại hàng: <i class="bi bi-chevron-down"></i><br>{{ $product->title }}
            </button>
            <div class="box-variant hidden" id="box-variant-{{ $product->idCart }}">
                <span class="fw-bold" style="font-size: clamp(12px, 2vw, 15px)">Chọn
                    loại hàng: </span>
                @foreach ($product->product->productVariants as $variant)
                        @php
                            $isDisabled = in_array($variant->sku, $carts->pluck('sku')->toArray());
                        @endphp
                        <button
                            class="changeVariant btn fw-normal {{ $variant->sku == $product->sku ? 'btn-tgnt' : 'btn-outline-tgnt' }}"
                            id="changeVariant-{{ $variant->sku }}" data-id="{{ $product->idCart }}" data-sku="{{ $variant->sku }}"
                            data-productSku="{{ $product->product->sku }}" data-quantity="{{ $product->quantity }}" {{ $isDisabled ? ' disabled' : '' }} {{ $variant->quantity == 0 ? ' disabled' : ''}}>
                            {!! $isDisabled ? '<i class="bi bi-check"></i>' : '' !!}
                            {{ $variant->title }}
                            ({{ $variant->quantity }})
                        </button>
                @endforeach
            </div>
        @endif
    </div>
    <div class="cart-item-quantity pe-xxl-5">
        <div class="cart-item-total text-center">
            <span class="price-total" id="price-total-{{ $product->idCart }}">
                {{ formatMoney(($product->price - ((int)$product->price * (int)$product->discount) / 100) * $product->quantityCart) }}</span>₫
            <input type="hidden" class="price-total" value="">
        </div>

        <div class="input-group input-spinner d-flex">
            <input type="button" value="-" class="btn-minus btn btn-sm" data-field="quantity">
            <input type="number" step="1" max="3" data-idCart="{{ $product->idCart }}" data-sku="{{ $product->sku }}"
                value="{{ $product->quantityCart }}" name="quantity" class="quantity-field form-control-sm form-input"
                readonly>
            <input type="button" value="+" class="btn-plus btn btn-sm" data-field="quantity">
        </div>
    </div>
    <div class="cart-item-remove">
        <button type="button" data-id="{{ $product->idCart }}"
            data-productSku="{{ $product->product->sku ?? $product->sku }}"
            class="removeItem btn btn-link p-0 text-danger" id="removeItem" style="text-decoration: none;"
            href="">x</button>
    </div>
</div>