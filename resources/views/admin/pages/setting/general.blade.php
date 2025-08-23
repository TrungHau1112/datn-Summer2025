@extends('admin.layout')

@section('template')
    @php
        $site_social = json_decode($setting->site_social);
    @endphp
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <form action="{{ route('setting.generalUpdate') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header">
                        Thông tin chung
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <x-input :label="'Tên website'" :name="'site_name'" :value="old('site_name', $setting['site_name'] ?? '')" />
                            </div>
                            <div class="col-6">
                                <x-input :label="'Số điện thoại'" :name="'site_phone'" :value="old('site_phone', $setting['site_phone'] ?? '')" />
                            </div>
                            <div class="col-6">
                                <x-input :label="'Email'" :name="'site_email'" :value="old('site_email', $setting['site_email'] ?? '')" />
                            </div>
                            <div class="col-6">
                                <x-input :label="'Địa chỉ'" :name="'site_address'" :value="old('site_address', $setting['site_address'] ?? '')" />
                            </div>
                            <div class="col-12">
                                <label for="">Nhúng bản đồ</label>
                                <textarea class="form-control" name="site_map" id="" cols="30"
                                    rows="5">{{ $setting->site_map }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="">Mạng xã hội</label>
                                <div class="input-group my-1">
                                    <div class="input-group-text" id="btnGroupAddon">
                                        <a href="https://www.facebook.com/{{  $site_social->facebook ?? '' }}"
                                            target="_blank"><i style="color: #016bdf" class="fa-brands fa-facebook"></i></a>
                                    </div>
                                    <input name="site_social[facebook]" value="{{ $site_social->facebook ?? '' }}" name=""
                                        type="text" class="form-control" placeholder="https://www.facebook.com/*****">
                                </div>
                                <div class="input-group my-1">
                                    <div class="input-group-text" id="btnGroupAddon">
                                        <a href="https://www.youtube.com/{{ '@' . $site_social->facebook ?? '' }}"
                                            target="_blank">
                                            <i style="color: #ff0033" class="fa-brands fa-youtube"></i></a>
                                    </div>
                                    <input name="site_social[youtube]" value="{{ $site_social->youtube ?? '' }}" type="text"
                                        class="form-control" placeholder="https://www.youtube.com/*****">
                                </div>
                                <div class="input-group my-1">
                                    <div class="input-group-text" id="btnGroupAddon">
                                        <a href="https://www.instagram.com/{{ $site_social->instagram ?? '' }}"
                                            target="_blank"><i style="color: #dc3e00"
                                                class="fa-brands fa-instagram"></i></a>
                                    </div>
                                    <input name="site_social[instagram]" value="{{ $site_social->instagram ?? '' }}"
                                        type="text" class="form-control" placeholder="https://www.instagram.com/*****">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-3">
                <x-save_back :model="'setting'" />
                <x-thumbnail :label="'Logo website'" :name="'site_logo'" :value="old(
            'site_logo',
            $setting->site_logo ?? '	https://placehold.co/600x600?text=Hinh%20Anh',
        )" />
            </div>

        </div>
        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}" />
    </form>
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <script>
        let collections = @json($collections ?? []);
    </script>
@endsection