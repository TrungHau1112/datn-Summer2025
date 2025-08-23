<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .content strong {
            color: #333;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cảm ơn bạn đã liên hệ với chúng tôi!</h1>
        </div>
        <div class="content">
            <p>Chúng tôi đã nhận được thông tin liên hệ của bạn và rất vui khi nhận được phản hồi từ bạn.</p>
            <p>Chúng tôi sẽ xem xét và phản hồi lại bạn trong thời gian sớm nhất!</p>
            <p>Nếu bạn có thêm câu hỏi, đừng ngần ngại liên hệ lại với chúng tôi qua <a href="mailto:support@example.com">email</a> hoặc gọi trực tiếp 0123456789.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} TheGioiNoiThat. Tất cả quyền được bảo lưu.</p>
            <p><a href="https://thegioinoithat.com">Truy cập website của chúng tôi</a></p>
        </div>
    </div>
</body>
</html>
