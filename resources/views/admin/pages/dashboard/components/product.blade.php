{{-- sản phẩm --}}
<div class="col-12">
    <div class="card">
        <div class="card-header pb-0 pt-2">
            <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link active" aria-selected="true">Sản
                        phẩm</button>
                </li>
            </ul>
        </div>
        <div class="card-body pt-2">
            <div class="row">
                <div class="col-lg-12 col-xl-12">
                    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="top-product"
                                data-bs-toggle="tab" data-bs-target="#top-product-pane" type="button" role="tab"
                                aria-controls="top-product-pane" aria-selected="true">Top sản phẩm bán chạy</button>
                        </li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="top-bad-product"
                                data-bs-toggle="tab" data-bs-target="#top-bad-product-pane" type="button"
                                role="tab" aria-controls="top-bad-product-pane" aria-selected="false"
                                tabindex="-1">Top sản phẩm bán chậm</button>
                        </li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="low-stock-product"
                                data-bs-toggle="tab" data-bs-target="#low-stock-product-pane" type="button"
                                role="tab" aria-controls="low-stock-product-pane" aria-selected="false"
                                tabindex="-1">Sản phẩm gần hết hàng</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        {{-- Top 10 sản phẩm bán chạy nhất --}}
                        <div class="tab-pane fade active show" id="top-product-pane" role="tabpanel"
                            aria-labelledby="top-product" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-hover table-hover">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Mã sản phẩm</th>
                                            <th>Đã bán</th>
                                            <th>Doanh thu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProducts as $key => $product)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a data-fancybox="gallery" href="{{ $product->thumbnail }}">
                                                        <img loading="lazy" width="40" class="rounded"
                                                            src="{{ $product->thumbnail }}"
                                                            alt="{{ $product->product_name }}">
                                                    </a>
                                                </td>
                                                <td><a
                                                        href="{{ route('client.product.detail', $product->slug) }}{{ $product->variant_code ? '?attr=' . rawurlencode(str_replace(' ', '', $product->variant_code)) : '' }}">{{ $product->product_name }}</a>
                                                </td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->total_quantity }}</td>
                                                <td>{{ number_format($product->total_revenue) }} VNĐ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- top sản phẩm bán tệ nhất --}}
                        <div class="tab-pane fade" id="top-bad-product-pane" role="tabpanel"
                            aria-labelledby="top-bad-product" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-hover table-hover">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Mã sản phẩm</th>
                                            <th>Đã bán</th>
                                            <th>Doanh thu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topLeastProducts as $key => $product)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a data-fancybox="gallery" href="{{ $product->thumbnail }}">
                                                        <img loading="lazy" width="40" class="rounded"
                                                            src="{{ $product->thumbnail }}"
                                                            alt="{{ $product->product_name }}">
                                                    </a>
                                                </td>
                                                <td><a
                                                        href="{{ route('client.product.detail', $product->slug) }}{{ $product->variant_code ? '?attr=' . rawurlencode(str_replace(' ', '', $product->variant_code)) : '' }}">{{ $product->product_name }}</a>
                                                </td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->total_quantity }}</td>
                                                <td>{{ number_format($product->total_revenue) }} VNĐ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- top sản phẩm gần hết --}}
                        <div class="tab-pane fade" id="low-stock-product-pane" role="tabpanel"
                            aria-labelledby="low-stock-product" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-hover table-hover">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Mã sản phẩm</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($lowStockProducts->count() > 0)
                                            @foreach ($lowStockProducts as $key => $product)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <a data-fancybox="gallery" href="{{ $product->thumbnail }}">
                                                            <img loading="lazy" width="40" class="rounded"
                                                                src="{{ $product->thumbnail }}"
                                                                alt="{{ $product->product_name }}">
                                                        </a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('client.product.detail', $product->slug) }}{{ $product->variant_code ? '?attr=' . rawurlencode(str_replace(' ', '', $product->variant_code)) : '' }}">{{ $product->product_name }}</a>
                                                    </td>
                                                    <td>{{ $product->sku }}</td>
                                                    <td>{{ $product->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100" class="text-center">Không có sản phẩm nào
                                                    gần hết hàng
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
