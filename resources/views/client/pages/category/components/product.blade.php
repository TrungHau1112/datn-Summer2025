<div class="row animate__animated animate__fadeIn listProduct mb-4">
    @if ($products->count() > 0)
        @foreach ($products as $item)
            <x-product_card :data="$item" />
        @endforeach
    @else
        <div class="col-12 py-15">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <img width="100" src="{{ asset('uploads/image/system/no_product.webp') }}" alt="">
                <div class="text-center mt-3">
                    Không có sản phẩm nào!
                </div>
            </div>

        </div>
    @endif
</div>
<div class="pagination_tgnt d-flex justify-content-center">
    {!! $products->links('pagination::bootstrap-4') !!}
</div>
<style>
    .product_container {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .product_container.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
