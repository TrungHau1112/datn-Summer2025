<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .content h3 {
            font-size: 20px;
            color: #4CAF50;
        }
        .content p {
            font-size: 16px;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            padding: 12px 24px;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ getSetting()->site_name }}!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h3>Xin chào, {{$account->name}}</h3>
            <p>Cảm ơn bạn đã đăng ký tài khoản! Chúng tôi rất vui khi có bạn tham gia. Để bắt đầu, vui lòng xác nhận địa chỉ email của bạn bằng cách nhấn vào nút dưới đây.</p>
            
            <!-- Button -->
            <div class="button-container">
                <a style="color: white" href="{{ route('client.auth.active', $account->email) }}" class="button">Kích hoạt tài khoản</a>
            </div>

            <p>Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} TheGioiDiDong. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
