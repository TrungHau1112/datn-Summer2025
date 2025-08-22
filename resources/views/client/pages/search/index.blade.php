@extends('client.layout')

@section('content')
  @php
    $brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : '';
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
    $keyword = request('keyword', '');
    $totalResults = $products->total();
  @endphp
  <div class="section-box">
    <div class="breadcrumbs-div">
    <div class="container">
      <ul class="breadcrumb">
      <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
      <li><a class="font-xs color-gray-500" href="#">
        @if($keyword)
          Tìm kiếm "{{ $keyword }}"
        @else
          Sản phẩm
        @endif
      </a></li>
      </ul>
    </div>
    </div>
  </div>
  <div class="section-box shop-template mt-30">
    <div class="container">
      @if($keyword)
        <div class="row mb-30">
          <div class="col-12">
            <div class="alert alert-info">
              <h5 class="mb-2">Kết quả tìm kiếm cho: "<strong>{{ $keyword }}</strong>"</h5>
              <p class="mb-0">Tìm thấy <strong>{{ $totalResults }}</strong> sản phẩm</p>
            </div>
          </div>
        </div>
      @endif
    <div class="row">
      <div class="col-lg-9 order-first order-lg-last">
      <div class="banner-ads-top mb-30"><a href="#"><img src="/client_asset_v1/imgs/page/shop/banner.png"
          alt="Ecom"></a></div>
      <div class="box-filters mt-0 pb-5 border-bottom">
        <div class="row">
        <div class="col-xl-2 col-lg-3 mb-10 text-lg-start text-center">
          <span class="font-sm color-gray-900 font-medium">{{ $totalResults }} sản phẩm</span>
        </div>
        </div>
      </div>
      <div class="row mt-20">
        @if($products->count() > 0)
          @foreach ($products as $product)
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card-grid-style-3">
            <div class="card-grid-inner">
            <div class="tools">
            <a class="btn btn-wishlist btn-tooltip mb-10" href="#" product-id="{{ $product->id }}"
            aria-label="Yêu thích"></a>
            </div>
            <div class="image-box">
              @if($product->discount > 0)
                <span class="label bg-brand-2">-{{ $product->discount }}%</span>
              @endif
              <a href="{{ route('client.product.detail', $product->slug) }}">
                <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}">
              </a>
            </div>
            <div class="info-right">
              <a class="font-xs color-gray-500" href="#">{{ $product->sku }}</a><br>
              <a class="color-brand-3 font-sm-bold" href="{{ route('client.product.detail', $product->slug) }}">
              {{ substr($product->name, 0, 50) }}
              @if (strlen($product->name) > 50)
            <span class="text-muted">...</span>
            @endif
              </a>
              <div class="price-info">
              <strong
                class="font-lg-bold color-brand-3 price-main">{{ formatMoney($product->price - ($product->price * $product->discount) / 100) }}</strong>
              @if ($product->discount > 0)
            <span class="color-gray-500 price-line">{{ formatMoney($product->price) }}</span>
            @endif
              </div>
              <div class="mt-20 box-btn-cart"><a class="btn btn-cart"
                href="{{ route('client.product.detail', $product->slug) }}">Xem chi tiết</a></div>
            </div>
            </div>
          </div>
          </div>
          @endforeach
        @else
          <div class="col-12">
            <div class="text-center py-5">
              <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/orderlist/5fafbb923393b712b964.png" 
                   width="100" alt="Không có sản phẩm" class="mb-3">
              <h5>Không tìm thấy sản phẩm nào</h5>
              <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác hoặc <a href="{{ route('client.home') }}">về trang chủ</a></p>
            </div>
          </div>
        @endif
      </div>
      <div class="d-flex justify-content-center">
        {{ $products->links() }}
      </div>
      </div>
      <div class="col-lg-3 order-last order-lg-first">
      <!-- Form tìm kiếm -->
      <div class="sidebar-border mb-20">
        <div class="sidebar-head">
          <h6 class="color-gray-900">Tìm kiếm</h6>
        </div>
        <div class="sidebar-content">
          <form method="GET" action="{{ route('client.search') }}">
            <div class="mb-3">
              <input type="text" name="keyword" class="form-control" 
                     placeholder="Nhập từ khóa..." value="{{ request('keyword') }}">
            </div>
            <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <button type="submit" class="btn btn-brand-3 w-100">Tìm kiếm</button>
          </form>
        </div>
      </div>

      <div class="sidebar-border mb-0">
        <div class="sidebar-head">
        <h6 class="color-gray-900">Thương hiệu</h6>
        </div>
        <div class="sidebar-content">
        <ul class="list-nav-arrow">
          @foreach ($brands as $brand)
          <li class="@if ($brand->id == $brand_id) brand-active @endif">
          <a href="{{ route('client.search', array_filter(array_merge(request()->all(), [
        'brand_id' => $brand->id,
        'category_id' => null
        ]))) }}">
            {{ $brand->name }}<span class="number">{{ $brand->products->count() }}</span>
          </a>
          </li>
      @endforeach
        </ul>
        </div>
      </div>
      <div class="sidebar-border mb-0">
        <div class="sidebar-head">
        <h6 class="color-gray-900">Danh mục sản phẩm</h6>
        </div>
        <div class="sidebar-content">
        <ul class="list-nav-arrow">
          @foreach ($categories as $category)
          <li class="@if ($category->id == $category_id) active @endif">
          <a href="{{ route('client.search', array_filter(array_merge(request()->all(), [
        'category_id' => $category->id,
        'brand_id' => null
        ]))) }}">
            {{ $category->name }}<span class="number">{{ $category->products->count() }}</span>
          </a>
          </li>
      @endforeach
        </ul>
        </div>
      </div>
      </div>

    </div>
    </div>
  </div>

  <style>
    .list-nav-arrow li.active a {
    color: #FD9636;
    }

    .list-nav-arrow li.active .number {
    background-color: #FD9636;
    color: #fff;
    transition-duration: 0.2s;
    }

    .list-nav-arrow li.brand-active a {
    color: #FD9636;
    }

    .list-nav-arrow li.brand-active .number {
    background-color: #FD9636;
    color: #fff;
    transition-duration: 0.2s;
    }
  </style>
@endsection

@push('script')
    <script src="/client_asset/custom/js/product/wishlist.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo lại wishlist cho các nút trong trang search
            TGNT.countWishList();
        });
    </script>
@endpush