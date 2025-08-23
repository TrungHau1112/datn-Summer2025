<div class="filterProduct">
    <div class="card-body show-product hidden">
        <x-filter :model="'collection'" :options="[
            'categoriesOther' => $categories ?? [],
            'categoriesRoom' => $categoryRoom ?? [],
        ]" />
        <p class="text-primary m-0 mt-2">
            Đã chọn: <span class="text-danger"><span class="countProduct">0</span> sản phẩm.
            </span>
        </p>
        <div id="content">
            @include('admin.pages.product.product.components.filterProduct')
        </div>
    </div>
    <h5 class="mt-4">Chi tiết Đơn Hàng</h5>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>SKU</th>
                <th>Tên Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Giá Tiền</th>
                <th>Tổng Tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="product-table-body" class="product-variant">
            @if (isset($order_details))
                @foreach ($order_details as $detail)
                    {{-- @dd($detail->product) --}}
                    <tr id="product-row-{{ $detail->sku }}" data-sku="{{ $detail->sku }}">
                        <td>
                            <a href="{{ $detail->product->thumbnail }}" data-fancybox="gallery">
                                <img src="{{ $detail->product->thumbnail }}" alt="{{ $detail->name }}"
                                    class="product-thumbnail">
                            </a>
                        </td>
                        <td>{{ $detail->sku }}</td>
                        <td class="text-wrap">{{ $detail->name }}</td>
                        <td class='fix-input'>
                            <input type="number" value="{{ $detail->quantity }}" class="product-quantity form-control"
                                data-price="{{ $detail->price }}" min="1" max="5" style="width: 60px;">
                        </td>
                        <td>{{ formatNumber($detail->price) }}</td>
                        <td class="total-price">{{ formatNumber($detail->price * $detail->quantity) }}</td>
                        <td><button type="button" class="btn btn-danger btn-sm rounded delete-product-btn">Xóa</button>
                        </td>
                        <td class="hidden">
                            <input type="text" name="product_id[]" value="{{ $detail->product_id }}">
                            <input type="text" class="product_quantity_input" name="quantity[]"
                                value="{{ $detail->quantity }}">
                            <input type="text" name="price[]" value="{{ $detail->price }}">
                            <input type="text" name="name_orderDetail[]" value="{{ $detail->name }}">
                            <input type="text" name="sku[]" value="{{ $detail->sku }}">
                            <input type="text" name="thumbnail[]" value="{{ $detail->product->thumbnail }}">
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="form-group mb-3">
        <label for="total_amount">Tổng tiền: <strong class="total_amount" id="preview_totle">0</strong></label>
        <input type="hidden" id="total_amount" name="total_amount" class="form-control" value="0">
    </div>
    <div class="card-footer">
        <div class="text-end">
            <a href="{{ route('order.index') }}" class="btn btn-danger">Quay lại</a>
            <button type="submit" class="submit-form btn btn-primary">Lưu</button>
        </div>
    </div>
</div>
<input type="hidden" name="skus" value="@json(isset($order_details) ? $order_details->pluck('sku') : '') "id="" />
<script>
    const skuInData = @json(isset($order_details) ? $order_details->pluck('sku') : '')
</script>


<script>
    var skus = @json($skus ?? []);
    let id = {!! json_encode(old('product_id', [])) !!};
    let sku = {!! json_encode(old('sku', [])) !!};
    let name = {!! json_encode(old('name_orderDetail', [])) !!};
    let price = {!! json_encode(old('price', [])) !!};
    let quantity = {!! json_encode(old('quantity', [])) !!};
    let thumbnail = {!! json_encode(old('thumbnail', [])) !!};

    let productOld = [];
    for (let i = 0; i < id.length; i++) {
        productOld.push({
            id: id[i],
            sku: sku[i],
            name: name[i],
            price: price[i],
            thumbnail: thumbnail[i],
            quantityOld: quantity[i],
        });
    }
</script>
