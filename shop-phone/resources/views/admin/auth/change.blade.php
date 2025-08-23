<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Thay đổi mật khẩu</title>
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
            <form action="{{ route('change.password') }}" method="POST" class="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="card my-5">
                    <div class="card-body">
                        <a href="#">
                            {{-- <img src="../assets/images/logo-dark.svg" class="mb-4 img-fluid" alt="img"> --}}
                        </a>
                        <div class="mb-4">
                            <h3 class="mb-2">
                                <b>Đặt lại mật khẩu</b>
                            </h3>
                            <p class="text-muted">Vui lòng nhập mật khẩu mới</p>

                        </div>
                       
                            <input type="hidden" name="email" value="{{ $email }}"  class="form-control disabled" id="floatingInput"
                            placeholder="">
                            <input type="hidden" name="token"  value="{{ $token }}" class="form-control" id="floatingInput"
                            placeholder="">

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" id="floatingInput"
                                placeholder="Nhập mật khẩu">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu xác nhận</label>
                            <input type="password" name="password_confirmation" class="form-control" id="floatingInput1"
                                placeholder="Nhập lại mật khẩu xác nhận">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
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
