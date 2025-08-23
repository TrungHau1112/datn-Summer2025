<!-- header -->


<header class="header sticky-bar">
    <div class="container">
        <div class="main-header">
            <div class="header-left">
                <div class="header-logo"><a class="d-flex" href="/"><img alt="Ecom"
                            src="{{ asset(getSetting()->site_logo) }}"></a></div>
                <div class="header-search">
                    <div class="box-header-search">
                        <form class="form-search" method="get" action="/tim-kiem">
                            <div class="box-category d-flex align-items-center justify-content-center">
                                <div class="box-category-item">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                            <div class="box-keysearch position-relative">
                                <input class="form-control font-xs" type="text" name="keyword" id="search_on"
                                    placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
                                <!-- Dropdown kết quả tìm kiếm -->
                                <div class="search_out card position-absolute w-100" id="search_out" style="display: none; z-index: 9999; top: 100%; margin-top: 5px;">
                                    <div class="search_header d-flex justify-content-between p-3" id="search_header">
                                        <!-- Header tìm kiếm -->
                                    </div>
                                    <div class="search_result p-3" id="search_result" style="max-height: 400px; overflow-y: auto;">
                                        <!-- Kết quả sản phẩm -->
                                    </div>
                                    <div class="search_category p-3" id="search_category">
                                        <!-- Kết quả danh mục -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="header-nav">
                    <nav class="nav-main-menu d-none d-xl-block">
                        <ul class="main-menu">
                            <li><a href="{{ route('client.home') }}">Trang chủ</a></li>
                            <li><a href="{{ route('client.product.index') }}">Sản phẩm</a></li>
                            {{-- <li><a href="{{ route('client.about.index') }}">Giới thiệu</a></li> --}}
                            <li><a href="{{ route('client.post.index') }}">Tin tức</a></li>
                            <li class="has-children"><a class="active" href="#">Thương hiệu</a>
                                <ul class="sub-menu two-col">
                                    @foreach (getCategory('room') as $item)
                                        <li><a href="{{ route('client.product.index', ['brand_id' => $item->id]) }}">
                                            {{ $item->name }}    
                                        </a></li>
                                    @endforeach
                                    
                                </ul>
                            </li>
                            <li><a href="{{ route('client.contact.index') }}">Hỗ trợ</a></li>
                        </ul>
                    </nav>
                    <div class="burger-icon burger-icon-white"><span class="burger-icon-top"></span><span
                            class="burger-icon-mid"></span><span class="burger-icon-bottom"></span></div>
                </div>
                <div class="header-shop">
                    <div class="d-inline-block box-dropdown-cart"><span class="font-lg icon-list icon-account"><span>Tài
                                khoản</span></span>
                        <div class="dropdown-account">
                            <ul>
                                @if (Auth::check())
                                    <li><a href="{{ route('client.account.index') }}">Tài khoản</a></li>
                                    <li><a href="{{ route('client.auth.logout') }}">Đăng xuất</a></li>
                                @else
                                    <li><a href="{{ route('client.auth.login') }}">Đăng nhập</a></li>
                                    <li><a href="{{ route('client.auth.register') }}">Đăng ký</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <a class="font-lg icon-list icon-wishlist" href="{{ route('client.wishlist.index') }}">
                        <span>Yêu thích</span><spans
                            class="wishlist_count number-item font-xs">{{ Auth::check() ? getWishlistCount() : 0 }}</spans></a>
                    <div class="d-inline-block">
                        <a href="{{ route('client.cart.index') }}" class="font-lg icon-list icon-cart">
                            <span>Giỏ hàng</span>
                            <span class="number-item font-xs cart_count">{{ Auth::check() ? getCartCount() : 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
</header>


<div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-content-area">
            <div class="mobile-logo"><a class="d-flex" href="index.html"><img alt="Ecom"
                        src="/client_asset_v1/imgs/template/logo.svg"></a></div>
            <div class="perfect-scroll">
                <div class="mobile-menu-wrap mobile-header-border">
                    <nav class="mt-15">
                        <ul class="mobile-menu font-heading">
                            <li class="has-children"><a class="active" href="index.html">Home</a>
                                <ul class="sub-menu">
                                    <li><a href="index.html">Homepage - 1</a></li>
                                    <li><a href="index-2.html">Homepage - 2</a></li>
                                    <li><a href="index-3.html">Homepage - 3</a></li>
                                    <li><a href="index-4.html">Homepage - 4</a></li>
                                    <li><a href="index-5.html">Homepage - 5</a></li>
                                    <li><a href="index-6.html">Homepage - 6</a></li>
                                    <li><a href="index-7.html">Homepage - 7</a></li>
                                    <li><a href="index-8.html">Homepage - 8</a></li>
                                    <li><a href="index-9.html">Homepage - 9</a></li>
                                    <li><a href="index-10.html">Homepage - 10</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="shop-grid.html">Shop</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-grid.html">Shop Grid</a></li>
                                    <li><a href="shop-grid-2.html">Shop Grid 2</a></li>
                                    <li><a href="shop-list.html">Shop List</a></li>
                                    <li><a href="shop-list-2.html">Shop List 2</a></li>
                                    <li><a href="shop-fullwidth.html">Shop Fullwidth</a></li>
                                    <li><a href="shop-single-product.html">Single Product</a></li>
                                    <li><a href="shop-single-product-2.html">Single Product 2</a></li>
                                    <li><a href="shop-single-product-3.html">Single Product 3</a></li>
                                    <li><a href="shop-single-product-4.html">Single Product 4</a></li>
                                    <li><a href="shop-cart.html">Shop Cart</a></li>
                                    <li><a href="shop-checkout.html">Shop Checkout</a></li>
                                    <li><a href="shop-compare.html">Shop Compare</a></li>
                                    <li><a href="shop-wishlist.html">Shop Wishlist</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="shop-vendor-list.html">Vendors</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-vendor-list.html">Vendors Listing</a></li>
                                    <li><a href="shop-vendor-single.html">Vendor Single</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="#">Pages</a>
                                <ul class="sub-menu">
                                    <li><a href="page-about-us.html">About Us</a></li>
                                    <li><a href="page-contact.html">Contact Us</a></li>
                                    <li><a href="page-careers.html">Careers</a></li>
                                    <li><a href="page-term.html">Term and Condition</a></li>
                                    <li><a href="page-register.html">Register</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                    <li><a href="page-404.html">Error 404</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="blog.html">Blog</a>
                                <ul class="sub-menu">
                                    <li><a href="blog.html">Blog Grid</a></li>
                                    <li><a href="blog-2.html">Blog Grid 2</a></li>
                                    <li><a href="blog-list.html">Blog List</a></li>
                                    <li><a href="blog-big.html">Blog Big</a></li>
                                    <li><a href="blog-single.html">Blog Single - Left sidebar</a></li>
                                    <li><a href="blog-single-2.html">Blog Single - Right sidebar</a></li>
                                    <li><a href="blog-single-3.html">Blog Single - No sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="page-contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="mobile-account">
                    <div class="mobile-header-top">
                        <div class="user-account"><a href="page-account.html"><img
                                    src="/client_asset_v1/imgs/template/ava_1.png" alt="Ecom"></a>
                            <div class="content">
                                <h6 class="user-name">Hello<span class="text-brand"> Steven !</span></h6>
                                <p class="font-xs text-muted">You have 3 new messages</p>
                            </div>
                        </div>
                    </div>
                    <ul class="mobile-menu">
                        <li><a href="page-account.html">My Account</a></li>
                        <li><a href="page-account.html">Order Tracking</a></li>
                        <li><a href="page-account.html">My Orders</a></li>
                        <li><a href="page-account.html">My Wishlist</a></li>
                        <li><a href="page-account.html">Setting</a></li>
                        <li><a href="page-login.html">Sign out</a></li>
                    </ul>
                </div>
                <div class="mobile-banner">
                    <div class="bg-5 block-iphone"><span class="color-brand-3 font-sm-lh32">Starting from $899</span>
                        <h3 class="font-xl mb-10">iPhone 12 Pro 128Gb</h3>
                        <p class="font-base color-brand-3 mb-10">Special Sale</p><a class="btn btn-arrow"
                            href="shop-grid.html">learn more</a>
                    </div>
                </div>
                <div class="site-copyright color-gray-400 mt-30">Copyright 2022 &copy; Ecom - Marketplace
                    Template.<br>Designed by<a href="http://alithemes.com/" target="_blank">&nbsp; AliThemes</a></div>
            </div>
        </div>
    </div>
</div>


<div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-content-area">
            <div class="mobile-logo"><a class="d-flex" href="index.html"><img alt="Ecom"
                        src="/client_asset_v1/imgs/template/logo.svg"></a></div>
            <div class="perfect-scroll">
                <div class="mobile-menu-wrap mobile-header-border">
                    <nav class="mt-15">
                        <ul class="mobile-menu font-heading">
                            <li class="has-children"><a class="active" href="index.html">Home</a>
                                <ul class="sub-menu">
                                    <li><a href="index.html">Homepage - 1</a></li>
                                    <li><a href="index-2.html">Homepage - 2</a></li>
                                    <li><a href="index-3.html">Homepage - 3</a></li>
                                    <li><a href="index-4.html">Homepage - 4</a></li>
                                    <li><a href="index-5.html">Homepage - 5</a></li>
                                    <li><a href="index-6.html">Homepage - 6</a></li>
                                    <li><a href="index-7.html">Homepage - 7</a></li>
                                    <li><a href="index-8.html">Homepage - 8</a></li>
                                    <li><a href="index-9.html">Homepage - 9</a></li>
                                    <li><a href="index-10.html">Homepage - 10</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="shop-grid.html">Shop</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-grid.html">Shop Grid</a></li>
                                    <li><a href="shop-grid-2.html">Shop Grid 2</a></li>
                                    <li><a href="shop-list.html">Shop List</a></li>
                                    <li><a href="shop-list-2.html">Shop List 2</a></li>
                                    <li><a href="shop-fullwidth.html">Shop Fullwidth</a></li>
                                    <li><a href="shop-single-product.html">Single Product</a></li>
                                    <li><a href="shop-single-product-2.html">Single Product 2</a></li>
                                    <li><a href="shop-single-product-3.html">Single Product 3</a></li>
                                    <li><a href="shop-single-product-4.html">Single Product 4</a></li>
                                    <li><a href="shop-cart.html">Shop Cart</a></li>
                                    <li><a href="shop-checkout.html">Shop Checkout</a></li>
                                    <li><a href="shop-compare.html">Shop Compare</a></li>
                                    <li><a href="shop-wishlist.html">Shop Wishlist</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="shop-vendor-list.html">Vendors</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-vendor-list.html">Vendors Listing</a></li>
                                    <li><a href="shop-vendor-single.html">Vendor Single</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="#">Pages</a>
                                <ul class="sub-menu">
                                    <li><a href="page-about-us.html">About Us</a></li>
                                    <li><a href="page-contact.html">Contact Us</a></li>
                                    <li><a href="page-careers.html">Careers</a></li>
                                    <li><a href="page-term.html">Term and Condition</a></li>
                                    <li><a href="page-register.html">Register</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                    <li><a href="page-404.html">Error 404</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="blog.html">Blog</a>
                                <ul class="sub-menu">
                                    <li><a href="blog.html">Blog Grid</a></li>
                                    <li><a href="blog-2.html">Blog Grid 2</a></li>
                                    <li><a href="blog-list.html">Blog List</a></li>
                                    <li><a href="blog-big.html">Blog Big</a></li>
                                    <li><a href="blog-single.html">Blog Single - Left sidebar</a></li>
                                    <li><a href="blog-single-2.html">Blog Single - Right sidebar</a></li>
                                    <li><a href="blog-single-3.html">Blog Single - No sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="page-contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="mobile-account">
                    <div class="mobile-header-top">
                        <div class="user-account"><a href="page-account.html"><img
                                    src="/client_asset_v1/imgs/template/ava_1.png" alt="Ecom"></a>
                            <div class="content">
                                <h6 class="user-name">Hello<span class="text-brand"> Steven !</span></h6>
                                <p class="font-xs text-muted">You have 3 new messages</p>
                            </div>
                        </div>
                    </div>
                    <ul class="mobile-menu">
                        <li><a href="page-account.html">My Account</a></li>
                        <li><a href="page-account.html">Order Tracking</a></li>
                        <li><a href="page-account.html">My Orders</a></li>
                        <li><a href="page-account.html">My Wishlist</a></li>
                        <li><a href="page-account.html">Setting</a></li>
                        <li><a href="page-login.html">Sign out</a></li>
                    </ul>
                </div>
                <div class="mobile-banner">
                    <div class="bg-5 block-iphone"><span class="color-brand-3 font-sm-lh32">Starting from $899</span>
                        <h3 class="font-xl mb-10">iPhone 12 Pro 128Gb</h3>
                        <p class="font-base color-brand-3 mb-10">Special Sale</p><a class="btn btn-arrow"
                            href="shop-grid.html">learn more</a>
                    </div>
                </div>
                <div class="site-copyright color-gray-400 mt-30">Copyright 2022 &copy; Ecom - Marketplace
                    Template.<br>Designed by<a href="http://alithemes.com/" target="_blank">&nbsp; AliThemes</a></div>
            </div>
        </div>
    </div>
</div>
<!-- end header -->