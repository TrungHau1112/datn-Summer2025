<div class="col-12 mb-4">
    <div class="info-card p-4 shadow-sm rounded bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h7 class="fw-bold text-tgnt m-0">
                <i class="bi bi-archive-fill me-2"></i> Đơn hàng của tôi
            </h7>
        </div>
        <hr>
        <div class="">
            <ul class="nav nav-line-bottom mb-3" id="pills-tabOne" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active pt-0" id="all-order-tab" data-bs-toggle="pill" href="#all-order"
                        role="tab" aria-controls="all-order" aria-selected="true">
                        Tất cả
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 order_status" id="pending-order-tab" data-status="pending"
                        data-url="{{ route('client.account.get-order-by-status', 'pending') }}" data-bs-toggle="pill"
                        href="#pending-order" role="tab" aria-controls="pending-order" aria-selected="false"
                        tabindex="-1">
                        Chờ xác nhận
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 order_status" id="payment-pending-order-tab" data-status="payment_pending"
                        data-url="{{ route('client.account.get-order-by-status', 'payment-pending') }}"
                        data-bs-toggle="pill" href="#payment-pending-order" role="tab"
                        aria-controls="payment-pending-order" aria-selected="false" tabindex="-1">
                        Chờ thanh toán
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 order_status" id="shipped-order-tab" data-status="shipped"
                        data-url="{{ route('client.account.get-order-by-status', 'shipped') }}" data-bs-toggle="pill"
                        href="#shipped-order" role="tab" aria-controls="shipped-order" aria-selected="false"
                        tabindex="-1">
                        Đang giao
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 order_status" id="delivered-order-tab" data-status="delivered"
                        data-url="{{ route('client.account.get-order-by-status', 'delivered') }}" data-bs-toggle="pill"
                        href="#delivered-order" role="tab" aria-controls="delivered-order" aria-selected="false"
                        tabindex="-1">
                        Hoàn thành
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 order_status" id="cancelled-order-tab" data-status="cancelled"
                        data-url="{{ route('client.account.get-order-by-status', 'cancelled') }}" data-bs-toggle="pill"
                        href="#cancelled-order" role="tab" aria-controls="cancelled-order" aria-selected="false"
                        tabindex="-1">
                        Đã hủy
                    </a>
                </li>
            </ul>
            <!-- Tab content  -->
            <div class="tab-content" id="pills-tabOneContent">
                {{-- Tất cả order --}}
                <div class="tab-pane tab-example-design fade active show" id="all-order" role="tabpanel"
                    aria-labelledby="all-order-tab">
                    <div class="search_product">
                        <form action="{{ route('client.account.get-order-all') }}" class="input-group search_order_all">
                            <input name="keyword_order" class="form-control keyword_order rounded" type="search"
                                placeholder="Tìm kiếm đơn hàng...">
                            <span class="input-group-append">
                                <button class="btn bg-white border border-start-0 ms-n10 rounded-0 rounded-end"
                                    type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </button>
                            </span>
                        </form>
                    </div>
                    <div class="mt-3 list_all_order" style="min-height: 150px">
                        {{-- js render ở đây --}}
                    </div>
                </div>
                <div class="tab-pane tab-example-html fade" id="pending-order" role="tabpanel"
                    aria-labelledby="pending-order-tab">
                    <div class="mt-3 list_pending_order" style="min-height: 150px">
                        {{-- js render ở đây --}}
                    </div>
                </div>
                <div class="tab-pane tab-example-html fade" id="payment-pending-order" role="tabpanel"
                    aria-labelledby="payment-pending-order-tab">
                    <div class="mt-3 list_payment_pending_order" style="min-height: 150px">
                        {{-- js render ở đây --}}
                    </div>
                </div>
                <div class="tab-pane tab-example-html fade" id="shipped-order" role="tabpanel"
                    aria-labelledby="shipped-order-tab">
                    <div class="mt-3 list_shipped_order" style="min-height: 150px">
                        {{-- js render ở đây --}}
                    </div>
                </div>
                <div class="tab-pane tab-example-html fade" id="delivered-order" role="tabpanel"
                    aria-labelledby="delivered-order-tab">
                    <div class="mt-3 list_delivered_order " style="min-height: 150px">
                        {{-- js render ở đây --}}
                    </div>
                </div>
                <div class="tab-pane tab-example-html fade" id="cancelled-order" role="tabpanel"
                    aria-labelledby="cancelled-order-tab">
                    <div class="mt-3 list_cancelled_order " style="min-height: 150px">
                        {{-- js render ở đây --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
