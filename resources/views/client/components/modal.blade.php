<!-- modal viết đánh giá -->
<div class="modal fade" id="modal_danhgia" tabindex="-1" role="dialog" aria-labelledby="modal_danhgiaTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_danhgiaTitle">Viết đánh giá của bạn</h5>
                <button type="button" class="btn-close btn-close-review" data-bs-dismiss="modal" aria-label="Close"
                    aria-label="Close" data-order-id="" data-order-url="{{ route('client.account.get-order-detail') }}"
                    data-bs-toggle="modal" data-bs-target="#orderDetail">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class=" py-3 mb-3">
                    <h4 class="mb-3 p-0">Đánh giá chung</h4>
                    <div class="d-flex justify-content-center">
                        <div class="rating">
                            <input type="radio" id="star-1" name="star-radio" value="5">
                            <label for="star-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-2" name="star-radio" value="4">
                            <label for="star-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-3" name="star-radio" value="3">
                            <label for="star-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-4" name="star-radio" value="2">
                            <label for="star-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-5" name="star-radio" value="1">
                            <label for="star-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                        </div>
                    </div>
                </div>
                <div class=" py-3 mb-3">
                    <h5>Nội dung đánh giá</h5>
                    <textarea name="content" type="text" class="form-control" rows="3"
                        placeholder="Bạn thích hay không thích điểu gì? Trải nghiệm sản phẩm của bạn như thế nào?"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-brand-3 btn-stnt add_review_tgnt">Đánh giá</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal viết đánh giá -->

<!-- modal viết đánh giá -->
<div class="modal fade" id="modal_xemdanhgia" tabindex="-1" role="dialog" aria-labelledby="modal_danhgiaTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="view-review">
            <div class="text-center" style="padding-top: 200px !important; padding-bottom: 200px !important">
                <div class="spinner-border text-tgnt" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- end modal viết đánh giá -->

{{-- edit account --}}
<div class="modal fade" id="editAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('client.account.edit-acccount') }}" class="modal-content edit-account-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Chỉnh sửa thông tin tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="name" class="form-label ms-3">Họ & Tên</label>
                    <input name="name" type="text" class="form-control rounded-5" placeholder="" id="name"
                        value="{{ $user->name ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="name" class="form-label ms-3">Số điện thoại</label>

                    <input name="phone" id="phone" type="number" class="form-control rounded-5" placeholder=""
                        id="phone" value="{{ $user->phone ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label ms-3">Email</label>
                    <input name="email" type="email" class="form-control rounded-5" placeholder="Email của bạn"
                        id="email" value="{{ $user->email ?? '' }}">
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label for="email" class="form-label ms-3">Tỉnh/thành phố</label>
                        <select name="province_id" id="province" class="form-control select-style1 select2 province location"
                            data-target="districts">
                            <option value="" disabled selected>Chọn tỉnh/Thành phố</option>
                            @if (isset($provinces))
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->code }}" @if ($user->province_id == $province->code) selected
                                    @endif>{{ $province->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="email" class="form-label ms-3">Quận/Huyện</label>
                        <select name="district_id" id="district" class=" form-control select-style1 select2 districts location"
                            data-target="wards">
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label ms-3">Phường/Xã</label>
                        <select name="ward_id" id="ward" class="form-control select-style1 wards select2 ">

                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-brand-3 saveEditAccount">Lưu</button>
            </div>
            <script>
                var province_id = '{{ isset($user->province_id) ? $user->province_id : old('province_id') }}'
                var district_id = '{{ isset($user->district_id) ? $user->district_id : old('district_id') }}'
                var ward_id = '{{ isset($user->ward_id) ? $user->ward_id : old('ward_id') }}'
            </script>
        </form>
    </div>
</div>

{{-- change pass --}}
<div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('client.account.change-pass-acccount') }}"
            class="modal-content change-pass-account-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Thay đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="password_old" class="form-label ms-3">Mật khẩu cũ</label>
                    <input name="password_old" placeholder="******************" type="password" class="form-control"
                        id="password_old" autocomplete="current-password">
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label ms-3">Mật khẩu mới</label>
                    <input name="password" placeholder="******************" type="password" class="form-control"
                        id="password" autocomplete="new-password">
                </div>
                <div class="form-group mb-3">
                    <label for="password_c" class="form-label ms-3">Xác nhận mật khẩu</label>
                    <input name="password_c" placeholder="******************" type="password" class="form-control"
                        id="password_c" autocomplete="new-password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-brand-3 changePassAccount">Lưu</button>
            </div>
            <script>
                var province_id = '{{ isset($user->province_id) ? $user->province_id : old('province_id') }}'
                var district_id = '{{ isset($user->district_id) ? $user->district_id : old('district_id') }}'
                var ward_id = '{{ isset($user->ward_id) ? $user->ward_id : old('ward_id') }}'
            </script>
        </form>
    </div>
</div>

{{-- Chi tiết đơn hàng --}}
<div class="modal fade" id="orderDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content change-pass-account-form">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body order_detail_html"
                style="position: relative;transition: all 0.1s ease-in !important">
                {{-- js render --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderCancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content change-pass-account-form">
            <div class="modal-body p-5">
                <div class="text-center">
                    <h5 class="text-dark">Bạn có chắc chắn muốn hủy đơn hàng này?</h5>
                </div>
                <div class="text-center mt-5">
                    <button class="btn btn-sm btn-outline-tgnt" data-bs-dismiss="modal" aria-label="Close">Quay
                        lại</button>
                    <button class="cancelOrder btn btn-sm btn-brand-3">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</div>

