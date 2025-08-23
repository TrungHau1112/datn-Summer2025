<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sản phẩm hiện có trong bộ sưu tập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cart">
                    <div class="main-left">
                        <div class="cart-container">
                            @foreach ($products as $product)
                                <div class="cart-item" id="cart-item-{{ $product->sku }}">
                                    <img alt="Black and white striped ceramic plate" height="100"
                                        src="{{ $product->thumbnail }}" width="100" />
                                    <div class="cart-item-details">
                                        <div class="cart-item-title">
                                            {{ $product->name }}
                                        </div>
                                        <div class="d-xxl-flex">
                                            <div class="cart-item-price">
                                                {{ formatNumber($product->price) }}
                                            </div>
                                            <div class="cart-item-old-price m-xxl-2 m-md-0">
                                                {{ formatNumber($product->price) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-item-subtitle">
                                        @if (isset($product->title))
                                            <button class="open-box-variant text-muted btn btn-link p-0">
                                                Phân loại hàng: <br>{{ $product->title }}
                                            </button>
                                        @endif
                                    </div>
                                    <div class="cart-item-check">
                                        <div class="checkbox-wrapper-27">
                                            <label class="checkbox check-collection" data-sku="{{ $product->sku }}"
                                                data-inventory="{{ $product->quantity }}"
                                                data-price="{{ $product->price }}"
                                                data-name="{{ $product->name }}"
                                                >
                                                <input type="checkbox" class="checkboxsku"
                                                    id="checkboxsku-{{ $product->sku }}" >
                                                <span class="checkbox__icon"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="hidden">
                                    <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                    <input type="hidden" name="sku[]" value="{{ $product->sku }}">
                                    <input type="hidden" name="name[]" value="{{ $product->name }}">
                                    <input type="hidden" name="price[]" value="{{ $product->price }}">
                                    <input type="hidden" name="inventory[]" value="{{ $product->quantity }}">
                                </div> --}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-tgnt" data-bs-dismiss="modal">Huỷ</button>
                <button type="button" class="btn btn-tgnt addMultiToCart" data-bs-dismiss="modal">Thêm vào giỏ
                    hàng</button>
            </div>
        </div>
    </div>
</div>
