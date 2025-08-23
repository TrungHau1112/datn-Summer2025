@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <x-form :config="$config" :model="$user ?? null">
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header">
                        Thông tin chung
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <x-input :label="'Email'" :name="'email'" :value="$user->email ?? ''" :require="true" />
                            </div>
                            <div class="col-lg-6">
                                <x-input :label="'Tên đầy đủ'" :name="'name'" :value="$user->name ?? ''" :require="true" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <x-select :name="'user_catalogue_id'" :options="$userCatalogues" :root="'Chọn vai trò'" :required="true" :label="'Vai trò'"
                                    :value="$user->user_catalogue_id ?? 0" />
                            </div>
                            <div class="col-lg-6">
                                <x-input :label="'Số điện thoại'" :name="'phone'" :value="$user->phone ?? ''" :require="true" />
                            </div>
                        </div>
                        @if (isset($config['method']) && $config['method'] !== 'edit')
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <x-input :label="'Mật khẩu'" :name="'password'" :value="''" :require="true"
                                        :type="'password'" />
                                </div>
                                <div class="col-lg-6">
                                    <x-input :label="'Mật khẩu xác nhận'" :name="'re_password'" :value="''" :require="true"
                                        :type="'password'" />
                                </div>
                            </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-4 form-group">
                                <label for="province" class="control-label">Tỉnh/Thành Phố</label>
                                <select name="province" id="province" class="form-control select2">
                                    <option value="" disabled selected>Chọn tỉnh/thành phố</option>
                                    <option value="HaNoi">Hà Nội</option>
                                    <option value="HoChiMinh">Hồ Chí Minh</option>
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="district" class="control-label">Quận/Huyện</label>
                                <select name="district" id="district" class="form-control select2">
                                    <option value="" disabled selected>Chọn quận/huyện</option>
                                    <option value="HaNoi">Hà Nội</option>
                                    <option value="HoChiMinh">Hồ Chí Minh</option>
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="ward" class="control-label">Phường/Xã</label>
                                <select name="ward" id="ward" class="form-control select2">
                                    <option value="" disabled selected>Chọn phường/xã</option>
                                    <option value="HaNoi">Hà Nội</option>
                                    <option value="HoChiMinh">Hồ Chí Minh</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <x-save_back :model="$config['model']" />
                <x-thumbnail :label="'Ảnh đại diện'" :name="'avatar'" :value="$user->avatar ?? '/uploads/system/no_img.jpg'" />
                <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.active')" :value="$user->publish ?? ''" />
            </div>
        </div>
    </x-form>
@endsection
