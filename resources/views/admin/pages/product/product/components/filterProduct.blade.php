@if (isset($products) && count($products))
    <div class="row mt-3 animate__animated animate__fadeIn">
        @foreach ($products as $key => $product)
            <div class="col-md-2 col-4 p-1 product-main" id="product-main-{{ $product->sku }}">
                    <label for="{{ $product->has_attribute == 1 ? 'disabled' : 'checkInput' . $product->sku }}"
                        style="cursor: pointer">
                        <input type="checkbox" id="checkInput{{ $product->sku }}" class="checkInput hidden"
                            data-id="{{ $product->id }}" data-sku="{{ $product->sku }}">
                        <div class="card product-item m-0" id="product-item{{ $product->sku }}">
                            <img class="img card-img-top p-1" height="100" src="{{ $product->thumbnail }}"
                                alt="Card image cap">
                            <div class="card-body p-2">
                                <span class="card-title text-limit text-primary"
                                    style="font-size: 12px">{{ $product->name }}</span>
                                <p class="card-text" style="font-size: 10px">{{ number_format($product->price) }} đ</p>
                            </div>
                        </div>
                        <p class="countVariant"></p>
                    </label>
                    @if (count($product->productVariants) > 0)
                        <div class="product-focus d-flex flex-column" style="z-index: 20;">
                            @foreach ($product->productVariants as $variant)
                                <div class="w-100">
                                    <label for="checkInput{{ $variant->sku }}" style="cursor: pointer;" class="w-100">
                                        <input type="checkbox" id="checkInput{{ $variant->sku }}"
                                            class="checkInput hidden" data-variantid="{{ $variant->id }}"
                                            data-sku="{{ $variant->sku }}" data-skuParent="{{ $product->sku }}">
                                        <div class="card m-0 p-2 w-100" id="product-item{{ $variant->sku }}">
                                            <span class="card-title text-limit text-primary"
                                                style="font-size: 12px">({{ $variant->title }})</span>
                                            <p class="card-text" style="font-size: 10px">
                                                {{ number_format($variant->price) }} đ</p>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
            </div>
        @endforeach
        {!! $products->links('pagination::bootstrap-4') !!}
    </div>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif


<style>
    .text-limit {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card {
        transition: background-color 0.3s ease;
    }

    .card>.card-img-top {
        aspect-ratio: 2/1;
    }

    .product-main {
        position: relative;

        &:hover .product-focus {
            visibility: visible;
            opacity: 1;
            top: 40%;
            transition: opacity 0.4s ease, top 0.4s ease;
        }
    }
    .countVariant {
        position: absolute;
        top: 0%;
        right: 0%;
        transform: translate(-50%, -50%);
        & span{
            background-color: rgb(240, 218, 74);
            padding:5px;
            border-radius: 10px;
        }
    }
    .product-focus {
        visibility: hidden;
        opacity: 0;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        z-index: 100;
        border-radius: 10px;
        position: absolute;
        background-color: #ececec;
        padding: 5px;
        width: 100%;
        height: 90%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        overflow: scroll;

        &::before {
            content: "Chọn phân loại";
            color: #a84448;
            text-align: center;
        }
    }

    .product-focus {
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #c3c3c3 #e6e6e6;
        border-radius: 10px;
    }
</style>
