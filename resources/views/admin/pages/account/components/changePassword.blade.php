@extends('admin.pages.account.index')
@section('information')
    <form method="post" action="{{ route('setting.account.update', ['type' => 'password']) }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                Đổi mật khẩu
            </div>
            <div class="card-body">
                <div class="col-lg-12">
                    <input type="hidden" name="current_password" value="{{ auth()->user()->password }}">
                    <label for="">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" name="password" id="" value="">
                    @error('password')
                        <small class="error text-danger">*{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-12 my-4">
                    <label for="">Mật khẩu mới</label>
                    <input type="password" class="form-control" name="password_new" id="" value="">
                    @error('password_new')
                        <small class="error text-danger">*{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-12">
                    <label for="">Nhập lại mật khẩu mới</label>
                    <input type="password" class="form-control" name="re_password_new" id="" value="">
                    @error('re_password_new')
                        <small class="error text-danger">*{{ $message }}</small>
                    @enderror
                </div>
                <div class="text-end my-4" style="gap: 5%">
                    <button type="submit" name="send" value="send"
                        class="btn btn-primary d-inline-flex justify-content-center" style="flex: 1">
                        <i data-feather="check-circle" class="me-1"></i>
                        Lưu
                    </button>
                </div>
            </div>
        </div>
        @php
            $roleName = $user->roles->pluck('name')->first();
        @endphp
        <input type="hidden" name="roles[]" value="{{ $roleName }}">
    </form>
@endsection
