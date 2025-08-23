<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-header h1 {
            color: #007bff;
        }

        .email-content {
            margin-bottom: 20px;
        }

        .email-footer {
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Cảm ơn bạn đã đặt hàng!</h1>
        </div>
        <div class="email-content">
            <p>Chào <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Cảm ơn bạn đã tin tưởng và mua sắm tại cửa hàng của chúng tôi! Đơn hàng của bạn đã được xác nhận và đang
                được xử lý.</p>
            <h3>Thông tin đơn hàng:</h3>
            <ul>
                <li><strong>Mã đơn hàng:</strong> {{ $order->code }}</li>
                <li><strong>Tổng tiền:</strong> {{ number_format($order->total) }} VND</li>
                <li><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
            </ul>
            <p>Chúng tôi sẽ thông báo cho bạn khi đơn hàng được giao. <a href="#">Kiểm tra đơn hàng</a></p>
        </div>
        <div class="email-footer">
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc hotline.</p>
            <p>Chúc bạn một ngày tốt lành!</p>
            <p><strong>{{ env('CMS_NAME') }}</strong></p>
        </div>
    </div>
</body>

</html>
