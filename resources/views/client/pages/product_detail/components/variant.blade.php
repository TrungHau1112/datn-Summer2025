@if (isset($product->attribute_category) && !is_null($product->attribute_category) && count($product->attribute_category) > 0)
    @foreach ($product->attribute_category as $item)
        <div class="attribute">
            <div class="mb-xxl-3 mb-2 attribute-item">
                <strong>{{ $item->name }}</strong> <span class="text-tgnt"></span><br>
                <div class="attribute-value mt-1">
                    @foreach ($item->attributes as $key => $attribute)
                        <button type="button" data-attributeId="{{ $attribute->id }}"
                            class="choose-attribute 
                               {{ isset($attrUrl) && $attrUrl ? (in_array($attribute->id, $attrUrl) ? 'active' : '') : ($key == 0 ? 'active' : '') }}">
                            {{ $attribute->value }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="mb-xxl-3 mb-2">
        <p class="text-muted">Không có biến thể cho sản phẩm này</p>
    </div>
@endif
<input type="hidden" name="product_id" value="{{ $product->id }}">
