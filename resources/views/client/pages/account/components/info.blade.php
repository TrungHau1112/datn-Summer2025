<div class="col-lg-12 mb-4">
    <div class="info-card p-4 shadow-sm rounded bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h7 class="fw-bold m-0 text-tgnt">
                <i class="bi bi-person-circle me-2"></i> Thông tin tài khoản
            </h7>

        </div>
        <hr>
        <ul class="list-unstyled mb-1 info_account">
            <ul>
                <li><strong>Tên:</strong> <span class="account_name">{{ $user->name }}</span></li>
                <li><strong>Số điện thoại:</strong> <span class="account_phone">{{ $user->phone }}</span>
                </li>
                <li><strong>Email:</strong> <span class="account_email">{{ $user->email }}</span></li>
                <li><strong>Địa chỉ: </strong> {{ $user->ward->name ?? 'Chưa có ' }},
                    {{ $user->district->name ?? 'Chưa có' }},
                    {{ $user->province->name ?? 'Chưa có' }} </li>
            </ul>
        </ul>
    </div>
    <div class="d-flex justify-content-between mt-3 gap-2">
        <button class="btn btn-brand-3 btn-sm" data-bs-toggle="modal" data-bs-target="#editAccount">
            Sửa thông tin
        </button>
        <button data-bs-toggle="modal" data-bs-target="#changePass" class="btn btn-brand-3 btn-sm">Đổi mật
            khẩu</button>
    </div>
</div>
