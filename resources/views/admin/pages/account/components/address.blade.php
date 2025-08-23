@extends('admin.pages.account.index')
@section('information')
    <form method="post" action="{{ route('setting.account.update', ['type' => 'address']) }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                Địa chỉ
            </div>
            <div class="row">
                <div class="card-body col-xl-8">
                    @include('admin.pages.user.components.location')
                    <div class="col-lg-12">
                        <x-input :label="'Địa chỉ cụ thể'" :name="'address'" :value="$user->address ?? ''" :required="false" />
                    </div>
                    <div class="text-end" style="gap: 5%">
                        <button type="submit" name="send" value="send"
                            class="btn btn-primary d-inline-flex justify-content-center" style="flex: 1">
                            <i data-feather="check-circle" class="me-1"></i>
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @php
            $roleName = $user->roles->pluck('name')->first();
        @endphp
        <input type="hidden" name="roles[]" value="{{ $roleName }}">
    </form>
@endsection
