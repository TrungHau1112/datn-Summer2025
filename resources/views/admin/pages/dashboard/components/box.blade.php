<div class="col col-md-6" style="cursor: pointer">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avtar bg-light-primary"><i class="ti ti-clipboard f-24"></i></div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-1">Đơn hàng trong tháng</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">+{{ $orderMonth }}</h4>
                        {!! growthRateHtml($orderGrowthRate) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col col-md-6" style="cursor: pointer">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avtar bg-light-warning"><i class="ti ti-currency-dollar f-24"></i></div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-1">Doanh thu trong tháng</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">{{ number_format($revenueMonth) }}</h4>
                        {!! growthRateHtml($revenueGrowthRate) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col col-md-6" style="cursor: pointer">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avtar bg-light-danger"><i class="ti ti-box f-24"></i></div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-1">Sản phẩm đang bán</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">{{ number_format($statistic['total_product']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col col-md-6" style="cursor: pointer">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avtar bg-light-success"><i class="ti ti-user f-24"></i></div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-1">Khách hàng</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">+{{ number_format($statistic['total_customer']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
