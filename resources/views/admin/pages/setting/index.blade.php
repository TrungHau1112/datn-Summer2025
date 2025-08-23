@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card mb-4 panel-section panel-section-settings.common panel-section-priority-0"
        id="panel-section-settings-settings.common" data-priority="0" data-id="settings.common" data-group-id="settings">
        <div class="card-header">
            <div>
                <h4 class="card-title">
                    Chung
                </h4>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="row">
                        <div class="col-auto d-flex align-items-center justify-content-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <svg class="icon  svg-icon-ti-ti-settings" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                    </path>
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-block mb-1">
                                <a class="text-decoration-none text-primary fw-bold" href="{{ route('setting.general') }}">
                                    Cài đặt chung
                                </a>
                            </div>
                            <div class="text-secondary mt-n1">
                                Xem và cập nhật cài đặt chung của bạn
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="row">
                        <div class="col-auto d-flex align-items-center justify-content-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <svg class="icon  svg-icon-ti-ti-slideshow" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M15 6l.01 0"></path>
                                    <path
                                        d="M3 3m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z">
                                    </path>
                                    <path d="M3 13l4 -4a3 5 0 0 1 3 0l4 4"></path>
                                    <path d="M13 12l2 -2a3 5 0 0 1 3 0l3 3"></path>
                                    <path d="M8 21l.01 0"></path>
                                    <path d="M12 21l.01 0"></path>
                                    <path d="M16 21l.01 0"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-block mb-1">
                                <a class="text-decoration-none text-primary fw-bold" href="{{ route('setting.slide') }}">
                                    Cài đặt Slider
                                </a>
                            </div>
                            <div class="text-secondary mt-n1">
                                Xem và cập nhật cài đặt slider của bạn
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
