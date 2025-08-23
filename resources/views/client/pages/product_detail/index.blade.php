@extends('client.layout')
@section('seo')
    @include('client.components.seo')
@endsection
@push('script')
    <script src="/client_asset/custom/js/cart/addToCart.js"></script>
    <script src="/client_asset/custom/js/product/comment_review.js"></script>
    <script src="/client_asset/custom/js/product/attribute.js"></script>
    <script>
        // Debug: Kiểm tra xem JavaScript đã load chưa
        console.log('Product detail page loaded');
        
        // Đảm bảo jQuery đã sẵn sàng
        $(document).ready(function() {
            console.log('Document ready');
            console.log('Choose attribute buttons:', $('.choose-attribute').length);
            console.log('Product has attribute:', {{ $product->has_attribute ?? 0 }});
            console.log('Product attribute_category exists:', {{ isset($product->attribute_category) ? 'true' : 'false' }});
            console.log('Local attributeCategory exists:', {{ isset($attributeCategory) ? 'true' : 'false' }});
            console.log('Attribute categories count:', {{ isset($attributeCategory) && $attributeCategory ? count($attributeCategory) : 0 }});
            
            // Test click event
            $('.choose-attribute').on('click', function() {
                console.log('Button clicked manually:', $(this).text());
                console.log('Button data:', $(this).data());
            });
            
            // Kiểm tra trạng thái ban đầu của các button
            $('.choose-attribute').each(function() {
                console.log('Button:', $(this).text(), 'Active:', $(this).hasClass('active'), 'Disabled:', $(this).prop('disabled'));
            });
            
            // Kiểm tra xem có attribute nào không
            if ($('.choose-attribute').length === 0) {
                console.log('No attribute buttons found - product may not have variants');
            }
        });
    </script>
@endpush
@section('content')
    <!-- abc -->
    @php
        $name = $product->name;
        $price = $product->price;
        $discount = $product->discount;
        $priceDiscount = $price - ($price * $discount) / 100;
        $albums = $product->albums;
        $description = $product->description;
        $category = $product->categories;
        $attributeCategory = isset($product->attribute_category) ? $product->attribute_category : null;
        $shortContent = $product->short_content ?? '';
        $attrUrl = $_GET['attr'] ?? '';
        if ($attrUrl) {
            $attrUrl = explode(',', $attrUrl);
        }

    @endphp

    <script>
        const product_id = {{ $product->id ?? 0 }};
    </script>
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li><a class="font-xs color-gray-500" href="{{ route('client.product.index') }}">Sản phẩm</a></li>
                    <li><a class="font-xs color-gray-500" href="#">{{ $name }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template product_ct">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="color-brand-3 mb-5 mw-80">{{ $name }}</h3>
                    <div class="row">
                        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-7 text-center text-sm-start mb-mobile">
                            <div class="rating mt-5 d-inline-block mr-20"><span
                                    class="font-sm color-brand-3 font-medium">SKU:</span><span
                                    class="font-sm color-gray-500">{{ $product->sku }}</span></div>
                        </div>
                        <div class="col-xl-6 col-lg-5 col-md-4 col-sm-5 text-center text-sm-end">
                            <div class="d-inline-block"><a href="#">
                                    <div class="d-inline-block align-middle ml-50">
                                        <div class="share-link">
                                            <span class="font-md-bold color-brand-3 mr-15 d-none d-lg-inline-block">Chia
                                                sẻ</span><a class="facebook hover-up" href="#"></a><a
                                                class="printest hover-up" href="#"></a><a class="twitter hover-up"
                                                href="#"></a><a class="instagram hover-up" href="#"></a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom pt-10 mb-20"></div>
                </div>
                <div class="col-lg-5 gallery-container">
                    {!! $albums !!}
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 mb-30">
                            <div class="box-product-price mb-5">
                                <h3 class="color-brand-3 price-main d-inline-block mr-10">{{ formatMoney($priceDiscount) }}
                                </h3>
                                @if ($discount > 0)
                                    <span
                                        class="color-gray-500 price-line font-xl line-througt">{{ formatMoney($price) }}</span>
                                @endif
                            </div>
                            <div class="product-description color-gray-900 mb-30">
                                {!! $shortContent !!}
                            </div>
                            <div class="mb-xxl-7 mb-2">
                                <strong>Danh mục: </strong>
                                @foreach ($category->where('is_room', 2) as $item)
                                    <a href="{{ route('client.product.index', ['category_id' => $item->id]) }}"
                                        class="cate_ctsp">
                                        <span class="">
                                            {{ $item->name }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mb-xxl-7 mb-2">
                                <strong>Thương hiệu: </strong>
                                @foreach ($category->where('is_room', 1) as $item)
                                    <a href="{{ route('client.product.index', ['brand_id' => $item->id]) }}"
                                        class="cate_ctsp">
                                        <span class="">
                                            {{ $item->name }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mb-xxl-7 mb-2 status_spct">
                                <strong>Tình trạng: </strong>
                                <span class="status-text {{ $product->quantity > 0 ? 'in-stock' : 'out-of-stock' }}">
                                    {{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </span>
                            </div>
                            @include('client.pages.product_detail.components.variant')
    <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="buy-product mt-25">
                                <div class="font-sm text-quantity mb-10">Số lượng</div>
                                <div class="stock-info mb-10">
                                    <span class="stock-label">Tồn kho:</span>
                                    <span class="stock-quantity" id="stock-display">{{ $product->quantity }}</span>
                                    <span class="stock-unit">sản phẩm</span>
                                </div>
                                <div class="box-quantity">
                                    <div class="input-quantity">
                                        <input class="font-xl color-brand-3" name="quantity" type="text"
                                            value="1" min="1" max="{{ $product->quantity }}">
                                        <span class="btn-minus" data-field="quantity">-</span>
                                        <span class="btn-plus" data-field="quantity">+</span>
                                    </div>
                                    <div class="button-buy button-buy-full">
                                        <a class="btn btn-buy addToCart" href="#">Thêm vào giỏ hàng</a>
                                    </div>
                                    <div class="ms-2 mt-1">
                                        <a product-id="{{ $product->id }}" class="mr-20" href="#">
                                            <span class="btn btn-wishlist mr-5 opacity-100 transform-none"
                                                data-login="{{ auth()->check() ? 'true' : 'false' }}"></span>
                                        </a>
                                    </div>
                                    <div class="hidden">
                                        <input type="hidden" name="price" id="price" value="{{ $priceDiscount }}">
                                        <input type="hidden" name="sku" id="sku" value="{{ $product->sku }}">
                                        <input type="hidden" name="inventory" class="inventory"
                                            value="{{ $product->quantity }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <div class="pl-30 pl-mb-0">
                                <div class="box-featured-product">
                                    <div class="item-featured">
                                        <div class="featured-icon"><img
                                                src="/client_asset_v1/imgs/page/product/delivery.svg" alt="Ecom"></div>
                                        <div class="featured-info"><span class="font-sm-bold color-gray-1000">
                                                Miễn phí giao hàng
                                            </span>
                                            <p class="font-sm color-gray-500 font-medium">Tất cả đơn hàng trên 1000đ</p>
                                        </div>
                                    </div>
                                    <div class="item-featured">
                                        <div class="featured-icon"><img src="/client_asset_v1/imgs/page/product/support.svg"
                                                alt="Ecom"></div>
                                        <div class="featured-info"><span class="font-sm-bold color-gray-1000">
                                                Hỗ trợ 24/7
                                            </span>
                                            <p class="font-sm color-gray-500 font-medium">Hotline: 0909090909</p>
                                        </div>
                                    </div>
                                    <div class="item-featured">
                                        <div class="featured-icon"><img
                                                src="/client_asset_v1/imgs/page/product/return.svg" alt="Ecom"></div>
                                        <div class="featured-info"><span class="font-sm-bold color-gray-1000">Return &
                                                Refund</span>
                                            <p class="font-sm color-gray-500 font-medium">Đổi trả trong 7 ngày</p>
                                        </div>
                                    </div>
                                    <div class="item-featured">
                                        <div class="featured-icon"><img
                                                src="/client_asset_v1/imgs/page/product/payment.svg" alt="Ecom"></div>
                                        <div class="featured-info"><span class="font-sm-bold color-gray-1000">
                                                Thanh toán
                                            </span>
                                            <p class="font-sm color-gray-500 font-medium">100% bảo mật</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-sidebar-product">
                                    <div class="banner-right h-500 text-center mb-30 banner-right-product"><span
                                            class="text-no font-11">No.9</span>
                                        <h5 class="font-md-bold mt-10">Sensitive Touch<br
                                                class="d-none d-lg-block">without
                                            fingerprint
                                        </h5>
                                        <p class="text-desc font-xs mt-10">Smooth handle and accurate click</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-bottom pt-30 mb-40"></div>
        </div>
    </section>
    <section class="section-box shop-template">
        <div class="container">
            <div class="pt-30 mb-10">
                <ul class="nav nav-tabs nav-tabs-product" role="tablist">
                    <li>
                        <a class="{{ request('tab') != 'danh-gia' ? 'active' : '' }}" href="#tab-description"
                            data-bs-toggle="tab" role="tab" aria-controls="tab-description"
                            aria-selected="{{ request('tab') != 'danh-gia' ? 'true' : 'false' }}">
                            Mô tả
                        </a>
                    </li>
                    <li>
                        <a class="{{ request('tab') == 'danh-gia' ? 'active' : '' }}" href="#tab-reviews"
                            data-bs-toggle="tab" role="tab" aria-controls="tab-reviews"
                            aria-selected="{{ request('tab') == 'danh-gia' ? 'true' : 'false' }}">
                            Đánh giá
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade {{ request('tab') != 'danh-gia' ? 'show active' : '' }}"
                        id="tab-description" role="tabpanel" aria-labelledby="tab-description">
                        <div class="display-text-short">
                            {!! $description !!}
                        </div>
                        <div class="mt-20 text-center">
                            <a class="btn btn-border font-sm-bold pl-80 pr-80 btn-expand-more">Xem thêm</a>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ request('tab') == 'danh-gia' ? 'show active' : '' }}" id="tab-reviews"
                        role="tabpanel" aria-labelledby="tab-reviews">
                        <div class="comments-area tab_review_tgnt">
                            {{-- Nội dung đánh giá động sẽ được load ở đây --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <style>
        .choose-attribute.active {
            background-color: #425A8B;
            color: #fff !important;
        }
        
        /* Giảm kích thước mô tả sản phẩm */
        .display-text-short {
            max-height: 300px;
            overflow: hidden;
            position: relative;
            font-size: 14px;
            line-height: 1.6;
            color: #666;
        }
        
        .display-text-short.expanded {
            max-height: none;
        }
        
        .display-text-short h1, 
        .display-text-short h2, 
        .display-text-short h3, 
        .display-text-short h4, 
        .display-text-short h5, 
        .display-text-short h6 {
            font-size: 16px;
            margin: 15px 0 10px 0;
            color: #333;
        }
        
        .display-text-short p {
            margin-bottom: 10px;
        }
        
        .display-text-short table {
            font-size: 13px;
            margin: 10px 0;
        }
        
        .display-text-short table td,
        .display-text-short table th {
            padding: 8px 12px;
        }
        
        /* Gradient overlay cho hiệu ứng fade */
        .display-text-short::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background: linear-gradient(transparent, white);
            pointer-events: none;
        }
        
        .display-text-short.expanded::after {
            display: none;
        }
        
        /* Nút "Xem thêm" */
        .btn-expand-more {
            font-size: 13px;
            padding: 8px 20px !important;
            margin-top: 10px;
        }
        
        /* Giảm kích thước tiêu đề sản phẩm */
        .product_ct h3.color-brand-3 {
            font-size: 24px;
            line-height: 1.3;
            margin-bottom: 15px;
        }
        
        /* Giảm kích thước mô tả ngắn */
        .product-description {
            font-size: 14px;
            line-height: 1.5;
            color: #666;
        }
        
        /* Styling cho nút số lượng */
        .input-quantity {
            position: relative;
            display: inline-flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .input-quantity input[name="quantity"] {
            border: none;
            text-align: center;
            width: 60px;
            padding: 8px 0;
            font-weight: bold;
            outline: none;
        }
        
        .btn-minus, .btn-plus {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 35px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        
        .btn-minus:hover, .btn-plus:hover {
            background: #e9ecef;
        }
        
        .btn-minus:active, .btn-plus:active {
            background: #dee2e6;
        }
        
        /* Styling cho thông tin tồn kho */
        .stock-info {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #666;
        }
        
        .stock-label {
            font-weight: 600;
            color: #333;
        }
        
        .stock-quantity {
            font-weight: bold;
            color: #2563eb;
            font-size: 16px;
        }
        
        .stock-unit {
            color: #666;
        }
        
        /* Hiển thị khi hết hàng */
        .stock-quantity.out-of-stock {
            color: #ef4444;
        }
        
        /* Disable nút thêm vào giỏ khi hết hàng */
        .btn-buy.disabled {
            background: #ccc !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }
        
        /* Styling cho trạng thái sản phẩm */
        .status-text.in-stock {
            color: #10b981;
            font-weight: 600;
        }
        
        .status-text.out-of-stock {
            color: #ef4444;
            font-weight: 600;
        }
        
        /* Styling cho nút chọn attribute */
        .choose-attribute {
            margin: 5px;
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 80px;
            text-align: center;
        }
        
        .choose-attribute:hover {
            border-color: #2563eb;
            color: #2563eb;
            transform: translateY(-1px);
        }
        
        .choose-attribute.active {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }
        
        .choose-attribute:disabled {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Đảm bảo nút active luôn có thể click */
        .choose-attribute.active {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: white !important;
            cursor: pointer !important;
        }
        
        .attribute-value {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        /* Chat Icon */
        #chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ffffff;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        #chat-toggle:hover {
            background: #aed4fc;
            transform: scale(1.1);
        }

        /* Chat Box */
        #chat-box {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            max-height: 500px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            z-index: 9999;
            overflow: hidden;
        }

        #chat-box.active {
            display: flex;
        }

        #chat-header {
            background: #007bff;
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 16px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #chat-header>button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        #chat-content {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
            max-height: 400px;
            background: #f8f9fa;
            font-size: 14px;
        }

        #chat-input-area {
            display: flex;
            border-top: 1px solid #e0e0e0;
            background: #fff;
        }

        #chat-input {
            flex: 1;
            border: none;
            padding: 12px 15px;
            font-size: 14px;
            outline: none;
        }

        #chat-input:focus {
            box-shadow: inset 0 0 0 2px #007bff;
            border-radius: 8px 0 0 8px;
        }

        #send-btn {
            border: none;
            background: #007bff;
            color: white;
            padding: 12px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        #send-btn:hover {
            background: #0056b3;
        }

        #send-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Suggestions */
        .suggestion {
            background: #f1f1f1;
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.3s ease;
            text-align: center;
        }

        .suggestion:hover {
            background: #e0e0e0;
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #007bff;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Message styling */
        .message {
            margin: 10px 0;
            display: flex;
            align-items: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.ai {
            justify-content: flex-start;
        }

        .message span {
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
            word-break: break-word;
            line-height: 1.5;
        }

        .message.user span {
            background: #007bff;
            color: white;
        }

        .message.ai span {
            background: #e9ecef;
            color: #000;
        }

        @media (max-width: 640px) {
            #chat-box {
                width: calc(100% - 40px);
                bottom: 70px;
                right: 20px;
            }

            #chat-toggle {
                bottom: 15px;
                right: 15px;
                width: 50px;
                height: 50px;
            }
        }
    </style>

    <!-- Chat Icon -->
    <div id="chat-toggle">
        <img src="{{ asset('uploads/image/chatbot/pngtree-vector-chatbot-icon-for-website-or-mobile-apps-vector-png-image_12721205.png') }}"
            alt="Chat Icon" style="width: 60px; height: 60px; object-fit: contain;">
    </div>

    <!-- Chat Box -->
    <div id="chat-box">
        <div id="chat-header">
            🤖 PaLu Tech Chào Bạn
            <button id="close-chat">✕</button>
        </div>
        <div id="chat-content">
            <div class="message ai">
                <span>Chào bạn! Mình là trợ lý AI, có thể giúp bạn tìm sản phẩm, bài viết, hoặc trả lời mọi câu hỏi. Hãy thử
                    nhấn vào các gợi ý bên dưới hoặc nhập câu hỏi của bạn! 😊</span>
            </div>
            <div id="suggestions">
                <div class="suggestion" onclick="sendSuggestion('Tìm iPhone 16')">Tìm iPhone 16</div>
                <div class="suggestion" onclick="sendSuggestion('Khuyến mãi hiện tại')">Khuyến mãi hiện tại</div>
            </div>
        </div>
        <div id="chat-input-area">
            <input id="chat-input" type="text" placeholder="Nhập câu hỏi của bạn...">
            <button id="send-btn">Gửi</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý nút "Xem thêm" cho mô tả sản phẩm
            const expandBtn = document.querySelector('.btn-expand-more');
            const descriptionDiv = document.querySelector('.display-text-short');
            
            if (expandBtn && descriptionDiv) {
                expandBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    descriptionDiv.classList.toggle('expanded');
                    
                    if (descriptionDiv.classList.contains('expanded')) {
                        expandBtn.textContent = 'Thu gọn';
                    } else {
                        expandBtn.textContent = 'Xem thêm';
                    }
                });
            }
            
            // Chat functionality
            const chatToggle = document.getElementById('chat-toggle');
            const chatBox = document.getElementById('chat-box');
            const closeChat = document.getElementById('close-chat');
            const sendBtn = document.getElementById('send-btn');
            const chatInput = document.getElementById('chat-input');
            const chatContent = document.getElementById('chat-content');

            if (!chatToggle || !chatBox || !closeChat || !sendBtn || !chatInput || !chatContent) {
                console.error('Error: One or more elements not found');
                return;
            }

            chatToggle.addEventListener('click', function () {
                console.log('Chat icon clicked');
                chatBox.classList.toggle('active');
                console.log('Chatbox class:', chatBox.classList);
                if (chatBox.classList.contains('active')) {
                    chatInput.focus();
                }
            });

            closeChat.addEventListener('click', function () {
                chatBox.classList.remove('active');
                console.log('Chatbox closed by close button');
            });

            sendBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && chatInput.value.trim()) {
                    sendMessage();
                }
            });

            function sendMessage() {
                const message = chatInput.value.trim();
                if (!message) return;

                appendMessage('Bạn', message, true);
                chatInput.value = '';
                chatInput.disabled = true;
                sendBtn.disabled = true;

                // Hiển thị hiệu ứng loading
                appendMessage('AI', '<span class="loading"></span> Đang xử lý...', false);

                fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message })
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Xóa thông báo loading
                        const lastMessage = chatContent.lastChild;
                        if (lastMessage && lastMessage.querySelector('.loading')) {
                            chatContent.removeChild(lastMessage);
                        }
                        appendMessage('AI', data.response, false);
                    })
                    .catch(error => {
                        // Xóa thông báo loading
                        const lastMessage = chatContent.lastChild;
                        if (lastMessage && lastMessage.querySelector('.loading')) {
                            chatContent.removeChild(lastMessage);
                        }
                        appendMessage('AI', '⚠️ Có lỗi xảy ra. Vui lòng thử lại.', false);
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        chatInput.disabled = false;
                        sendBtn.disabled = false;
                        chatInput.focus();
                    });
            }

            window.sendSuggestion = function (message) {
                chatInput.value = message;
                sendMessage();
            }

            function appendMessage(sender, message, isUser = false) {
                const div = document.createElement('div');
                div.className = `message ${isUser ? 'user' : 'ai'}`;
                div.innerHTML = `<span>${message}</span>`;
                chatContent.appendChild(div);
                chatContent.scrollTop = chatContent.scrollHeight;
            }
        });
    </script>

@endsection
