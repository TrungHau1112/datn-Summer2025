<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/client_asset/library/bootstrap5.3/dist/css/theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/client_asset/custom/css/color.css') }}" />
    <link rel="stylesheet" href="{{ asset('/client_asset/custom/css/auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('/client_asset/library/icon/feather-webfont/dist/feather-icons.css') }}" />
    <link href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.js"></script>
    <title>Đăng nhập</title>
    <style>

    </style>
</head>

<body class="auth container text-white" style="">
    <div class="card border-0">
        <div class="row g-0">
            <div class="col-md-6 left">
                <div class="d-flex justify-content-center align-items-center h-100" style="flex-direction: column">
                    <p class="text-white">Vui lòng đặt lại mật khẩu mới của bạn</p>
                </div>
            </div>
            <div class="col-md-6 right">
                <h2 class="mb-4 text-center text-white">Thay đổi mật khẩu</h2>
                <form action="{{ route('client.auth.post-reset', ['user' => $user, 'token' => $token]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" class="form-control"
                        id="floatingInput" placeholder="">
                    <div class="form-group mb-3">
                        <input type="password" name="password" class="form-control input-tgnt"
                            placeholder="Mật khẩu mới">
                        @error('password')
                            <small class="text-danger">*{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control input-tgnt"
                            placeholder="Xác nhận mật khẩu mới">
                        @error('password_confirmation')
                            <small class="text-danger">*{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-tgnt w-100">Cập nhật mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/jquery-3.1.1.min.js"></script>
    @include('client.components.alert')
</body>

</html>
