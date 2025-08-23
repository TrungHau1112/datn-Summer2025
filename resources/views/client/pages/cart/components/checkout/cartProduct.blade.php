    <h5 class="mt-4">Đơn hàng của bạn</h5>
    <table class="table table-striped mt-3">
        {{-- <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Sản Phẩm</th>
            </tr>
        </thead> --}}
        <tbody id="product-table-body" class="product-variant">
            @if (isset($products))
                @foreach ($products as $product)
                    {{-- @dd($detail->product) --}}
                    <tr data-id="">
                        <td>
                            <a href="{{ $product->thumbnail }}" class="product-thumbnail " data-fancybox="gallery">
                                <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"width="50">
                            </a>
                        </td>
                        <td class="name position-relative">
                            {{ $product->name }}
                            <span class="quantity btn btn-tgnt">{{ formatNumber($product->quantityCart) }}</span>
                        </td>
                        <td class="hidden">
                            <input type="hidden" name="product_id[]"
                                value="{{ $product->product_id ?? $product->id }}">
                            <input type="hidden" class="product_quantity_input" name="quantity[]"
                                value="{{ $product->quantityCart }}">
                            <input type="hidden" name="price[]"
                                value="{{ $product->price - ($product->price * $product->discount) / 100 }}">
                            <input type="hidden" name="name_orderDetail[]" value="{{ $product->name }}">
                            <input type="hidden" name="sku[]" value="{{ $product->sku }}">
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <p class="cart-total">Tổng tiền: <span id="cart-total">{{ formatNumber($total) }}</span>đ</p>
    <input type="hidden" id="cart-total-input" value="{{ $total }}"></input>

    <style>
        th,
        td {
            text-align: center;
            padding: 10px 0 !important;
        }

        .name {
            padding-right: 40px !important;
        }

        .quantity {
            position: absolute;
            top: 0%;
            right: 0%;
            transform: translate(-0%, -50%);
        }
    </style>
    <script>
        let id = {!! json_encode(old('product_id', [])) !!};
        let sku = {!! json_encode(old('sku', [])) !!};
        let name = {!! json_encode(old('name_orderDetail', [])) !!};
        let price = {!! json_encode(old('price', [])) !!};
        let quantity = {!! json_encode(old('quantity', [])) !!};
        let total = {!! json_encode(old('total', [])) !!};
        let thumbnail = {!! json_encode(old('thumbnail', [])) !!};

        // let productVariants = [];
        // for (let i = 0; i < id.length; i++) {
        //     productVariants.push({
        //         id: id[i],
        //         sku: sku[i],
        //         name: name[i],
        //         price: price[i],
        //         thumbnail: thumbnail[i],
        //         quantity: quantity[i],
        //     });
        // }
    </script>
