<section class="section-box pt-50">
    <div class="container">
        <div class="head-main">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <h3 class="mb-5">Sản phẩm bán chạy</h3>
                    <p class="font-base color-gray-500">Sản phẩm bán chạy nhất trong tháng.</p>
                </div>
            </div>
        </div>
        <div class="list-products-5">
            @foreach ($product_bestsellers as $product)
                <x-product_card :data="$product" dataType="bestseller" />
            @endforeach
        </div>
    </div>
</section>
