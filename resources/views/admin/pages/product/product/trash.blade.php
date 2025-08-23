@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header text-end">
            <a href="{{ route('product.index') }}" class="btn btn-primary">Danh sách sản phẩm</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phảm</th>
                            <th>Giá tiền</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Danh mục</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($products) && count($products))
                            @foreach ($products as $key => $product)
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <a data-fancybox="gallery" href="{{ $product->thumbnail }}">
                                            <img loading="lazy" width="80" class="rounded"
                                                src="{{ $product->thumbnail }}" alt="{{ $product->name }}">
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
                                            <x-restore :id="$product->id" :model="ucfirst($config['model'])" />
                                            <x-delete :id="$product->id" :model="ucfirst($config['model'])" :destroy="true" />
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="100" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
