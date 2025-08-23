{{-- tổng quan --}}
<div class="col-12">
    <div class="card">
        <div class="card-header pb-0 pt-2">
            <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link active">Khách hàng</button>
                </li>
            </ul>
        </div>
        <div class="card-body pt-2">
            <div class="row">
                <div class="col-lg-12 col-xl-12">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="analytics-tab-1-pane" role="tabpanel"
                            aria-labelledby="analytics-tab-1" tabindex="0">
                            <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation"><button class="nav-link active"
                                        id="new-customer" data-bs-toggle="tab" data-bs-target="#new-customer-pane"
                                        type="button" role="tab" aria-controls="new-customer-pane"
                                        aria-selected="true">Khách hàng mới</button>
                                </li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="top-customer"
                                        data-bs-toggle="tab" data-bs-target="#top-customer-pane" type="button"
                                        role="tab" aria-controls="top-customer-pane" aria-selected="false"
                                        tabindex="-1">Top khách hàng mua nhiều nhất</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                {{-- Top 10 sản phẩm bán chạy nhất --}}
                                <div class="tab-pane fade active show" id="new-customer-pane" role="tabpanel"
                                    aria-labelledby="new-customer" tabindex="0">
                                    <div class="table-responsive">
                                        <canvas id="newCustomersChart" width="400" height="160"></canvas>
                                    </div>
                                </div>
                                {{-- top sản phẩm bán tệ nhất --}}
                                <div class="tab-pane fade" id="top-customer-pane" role="tabpanel"
                                    aria-labelledby="top-customer" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-hover">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Hình ảnh</th>
                                                    <th>Họ & Tên</th>
                                                    <th>Email</th>
                                                    <th>Số sản phẩm đã mua</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($topCustomersByQuantity as $key => $item)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>
                                                            <img loading="lazy" width="40" class="rounded"
                                                                src="https://ui-avatars.com/api/?background=random&name={{ $item->name }}"
                                                                alt="{{ $item->name }}">
                                                        </td>
                                                        <td class=""><a
                                                                href="#{{ $item->name }}">{{ $item->name }}</a>
                                                        </td>
                                                        <td class="">{{ $item->email }}</td>
                                                        <td class="">{{ $item->total_quantity }}</td>
                                                    </tr>
                                                @endforeach
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
    </div>
</div>
