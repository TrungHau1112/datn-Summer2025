<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- [Meta] -->
    <title>Quên mật khẩu</title>
    @include('admin.components.head_cdn')

</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <form action="{{route('auth.admin.forget')}}" method="POST" class="auth-form">
                @csrf
                <div class="card my-5">
                    <div class="card-body">
                        <a href="#">
                            {{-- <img src="{{ asset('admin_asset/images/logo.gif') }}" width="50" class="mb-4 img-fluid logo-lg" alt="img"> --}}
                        </a>
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0">
                                <b>Quên mật khẩu</b>
                            </h3>
                            <a href="{{ route('auth.login') }}" class="link-primary">Quay về đăng nhập</a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ email</label>
                            <input type="email" name="email" class="form-control" id="floatingInput"
                                placeholder="Nhập địa chỉ email của bạn">
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <p class="mt-4 text-sm text-muted">Đừng quên kiểm tra hộp thư..</p>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Gửi email đặt lại mật khẩu</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.components.alert')
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('admin_asset/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin_asset/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin_asset/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin_asset/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('admin_asset/js/pcoded.js') }}"></script>
    <script src="{{ asset('admin_asset/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change("light");
    </script>
    <script>
        change_box_container("false");
    </script>
    <script>
        layout_caption_change("true");
    </script>
    <script>
        layout_rtl_change("false");
    </script>
    <script>
        preset_change("preset-9");
    </script>
    <script>
        main_layout_change("vertical");
    </script>

</body>
<!-- [Body] end -->

</html>
