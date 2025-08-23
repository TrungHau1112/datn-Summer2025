@if (isset($products) && count($products))
    @foreach ($products as $key => $product)
        <tr class="animate__animated animate__fadeIn">
            <td class="">
                <div class="form-check">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $product->id }}" value="{{ $product->id }}">
                    <label class="form-check-label" for="ustomCheckbox{{ $product->id }}"></label>
                </div>
            </td>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>
                <a data-fancybox="gallery" href="{{ $product->thumbnail }}">
                    <img loading="lazy" width="80" class="rounded" src="{{ $product->thumbnail }}"
                        alt="{{ $product->name }}">
                </a>
            </td>

            <td class="text-primary text-wrap">
                <a href="{{ route('product.edit', $product->id) }}">{{ $product->name }}</a> <br>
                {!! $product->discount != 0
                    ? "<span class='badge bg-light-danger'>Có giảm giá - $product->discount%</span>"
                    : '' !!}
                {!! $product->is_featured == 1 ? '<span class="badge bg-light-danger">Nổi bật</span>' : '' !!}
                {!! $product->has_attribute == 1 ? '<span class="badge bg-light-danger">Có biến thể</span>' : '' !!}

            </td>
            <td class="text-center">
                @php
                    $product->has_attribute == 1
                        ? ($product->priceAttr->min == $product->priceAttr->max
                            ? ($price = number_format($product->priceAttr->min))
                            : ($price =
                                number_format($product->priceAttr->min) .
                                ' - ' .
                                number_format($product->priceAttr->max)))
                        : ($price = number_format($product->price));
                    echo $price;
                @endphp
                {{-- @dd($product->priceAttr)
                {{ number_format($product->price) }} đ --}}
            </td>
            <td class="text-center">{{ number_format($product->quantity) }}</td>
            <td class="text-center">{{ $product->sku }}</td>
            <td class="text-start text-wrap">
                @if ($product->categories->count())
                    @foreach ($product->categories as $category)
                        <a href="{{ route('client.category.index', $category->slug) }}"><span
                                class="badge bg-light-danger">{{ $category->name }}</span></a>
                    @endforeach
                @else
                    <span class="badge bg-light-danger">Không có danh mục</span>
                @endif
            </td>

            <td class="text-center">
                <x-switchvip :value="$product" :model="ucfirst($config['model'])" />
            </td>
            <td class="text-center table-actions">
                <ul class="list-inline me-auto mb-0">
                    <x-edit :id="$product->id" :model="$config['model']" />
                    <x-delete :id="$product->id" :model="ucfirst($config['model'])" />
                </ul>
            </td>
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="100">
            {!! $products->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
