<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Đăng nhập</title>
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
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
                <img src="{{ asset('admin_asset/images/authentication/img-auth-sideimg.jpg') }}" alt="images"
                    class="img-fluid img-auth-side" />
            </div>
            <div class="auth-form">
                <div class="card my-5">
                    <form action="{{ route('auth.login') }}" method="POST" class="card-body">
                        @csrf
                        {{-- <div class="text-center">
                            <a href="#">
                                <img src="{{ asset('admin_asset/images/logo-dark.svg') }}" alt="img" />
                            </a>
                            <div class="d-grid my-3">
                                <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                    <img src="{{ asset('admin_asset/images/authentication/facebook.svg') }}"
                                        alt="img" />
                                    <span>Đăng nhập bằng Facebook</span>
                                </button>
                                <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                    <img src="{{ asset('admin_asset/images/authentication/twitter.svg') }}"
                                        alt="img" />
                                    <span>Đăng nhập bằng Twitter</span>
                                </button>
                                <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                    <img src="{{ asset('admin_asset/images/authentication/google.svg') }}"
                                        alt="img" />
                                    <span>Đăng nhập bằng Google</span>
                                </button>
                            </div>
                        </div> --}}
                        {{-- <div class="saprator my-3">
                            <span>HOẶC</span>
                        </div> --}}
                        <h4 class="text-center f-w-500 mb-3">Đăng nhập vào trang quản trị</h4>
                        <div class="mb-3">
                            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror"
                                id="floatingInput" placeholder="Địa chỉ email" />
                            @error('email')
                                <small class="text-danger">*{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="Mật khẩu" />
                        </div>
                        <div class="d-flex mt-1 justify-content-between align-items-center">
                            <div class="form-check">
                                {{-- <input class="form-check-input input-primary" type="checkbox" id="customCheckc1"
                                    checked="" />
                                <label class="form-check-label text-muted" for="customCheckc1">Nhớ tôi?</label> --}}
                            </div>
                            <h6 class="text-secondary f-w-400 mb-0">
                                <a href="{{ route('auth.admin.forget') }}">Quên mật khẩu?</a>
                            </h6>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" name="send" class="btn btn-primary">Đăng nhập</button>
                        </div>
                        {{-- <div class="d-flex justify-content-between align-items-end mt-4">
                            <h6 class="f-w-500 mb-0">Bạn chưa có tài khoản?</h6>
                            <a href="#" class="link-primary">Tạo tài khoản</a>
                        </div> --}}
                    </form>
                </div>
            </div>
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
