@extends('client.layout')

@section('content')
    <style>
        /* --- Home polish --- */
        .hero {
            padding: 72px 0;
            background: linear-gradient(135deg, #f6f9ff, #eef3ff);
            border-radius: 16px;
            overflow: hidden;
        }
        .hero h1 { font-size: 40px; font-weight: 800; margin: 0 0 8px; }
        .hero h2 { font-size: 22px; color: #2563eb; margin: 0 0 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .hero p { color: #4b5563; margin: 0 0 20px; line-height: 1.65; }
        .btn-primary-ui { background:#2563eb; color:#fff; padding:12px 20px; border-radius:12px; font-weight:700; border:none; display:inline-block; }
        .btn-primary-ui:hover { background:#1d4ed8; transform: translateY(-1px); }
        .trust-bar { display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; margin: 20px 0 0; align-items:stretch; }
        .trust-item { background:#fff; border:1px solid #eef; border-radius:12px; padding:12px 14px; display:flex; gap:10px; align-items:center; justify-content:flex-start; min-height:56px; }
        .trust-item svg { width:20px; height:20px; color:#2563eb; flex:0 0 20px; }
        .trust-item .badge-ui { margin-right:6px; }
        .trust-item strong { color:#111827; }
        .product-card { border:1px solid #eef; border-radius:14px; overflow:hidden; transition:.2s; background:#fff; }
        .product-card:hover { box-shadow:0 10px 24px rgba(37,99,235,.12); transform: translateY(-2px); }
        .price { color:#ef4444; font-weight:800; }
        .badge-ui { background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:8px; font-size:12px; }
        /* News cards uniform */
        .card-grid-style-1 .image-box img { width: 100%; height: 220px; object-fit: cover; border-radius: 12px; }
        .card-grid-style-1 h4 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 52px; }
        .swiper-group-4 .swiper-slide { height: auto; }
        .card-grid-style-1 { padding-bottom: 10px; }
        /* Chat Icon alignment */
        #chat-toggle { border: 1px solid #e5e7eb; }
        #chat-toggle img { width: 36px; height: 36px; }
        /* Chat Icon */
        #chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: transparent;
            /* Xóa nền màu xanh */
            color: white;
            width: 50px;
            /* Điều chỉnh kích thước icon */
            height: 50px;
            /* Điều chỉnh kích thước icon */
            border-radius: 50%;
            /* Giữ hình tròn cho icon */
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            z-index: 10000;
            transition: transform 0.3s ease;
        }

        /* Thêm hiệu ứng hover khi nhấn */
        #chat-toggle:hover {
            transform: scale(1.1);
            /* Phóng to icon khi hover */
        }

        /* Điều chỉnh hình ảnh icon */
        #chat-toggle img {
            width: 40px;
            /* Điều chỉnh kích thước của icon */
            height: 40px;
            border-radius: 50%;
            /* Giữ cho icon tròn */
            object-fit: cover;
            /* Cắt hình ảnh gọn gàng */
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

        /* Product and post links */
        .product-link {
            display: inline-block;
            margin-top: 5px;
            padding: 5px 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
        }

        .product-link:hover {
            background: #0056b3;
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

    @include('client.pages.home.components.banner')
    @include('client.pages.home.components.category')
    @include('client.pages.home.components.sellers')
    @include('client.pages.home.components.selling')
    @include('client.pages.home.components.news')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Các section khác -->
    <section class="section-box mt-90 mb-50">
        <!-- Nội dung của bạn ở đây -->
    </section>

    <section class="section-box box-newsletter">
        <!-- Nội dung của bạn ở đây -->
    </section>

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