<nav class="pc-sidebar" style="z-index: 1 !important">
    <div class="navbar-wrapper">
        <div class="m-header d-flex justify-content-center">
            <a href="{{ route('dashboard.index') }}" class="b-brand text-primary">
                <img src="{{ asset(getSetting()->site_logo) }}" width="150" alt="">
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode(auth()->user()->name) }}" 
                            alt="user-image" class="user-avatar wid-45 rounded-circle" width="60" height="50"/>
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small>Admin</small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="{{ route('setting.account.index',['type' => 'your-information']) }}">
                                <i class="ti ti-user"></i>
                                <span>Tài khoản</span>
                            </a>
                            <a href="{{ route('auth.logout') }}">
                                <i class="ti ti-power"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>
                <li class="pc-item">
                    <a href="{{ route('dashboard.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Bảng điều khiển</span>
                    </a>
                </li>
                @foreach (__('sidebar.function') as $key => $val)
                    @can(ucfirst($val['route'][0]) . ' index')
                        @if ($val['module'])
                            <li class="pc-item pc-hasmenu">
                                <a href="#!" class="pc-link">
                                    <span class="pc-micon">
                                        {!! $val['icon'] !!}
                                    </span>
                                    <span class="pc-mtext">{{ $val['name'] }}</span>
                                    <span class="pc-arrow">
                                        <i data-feather="chevron-right"></i>
                                    </span>
                                </a>
                                <ul class="pc-submenu">
                                    @foreach ($val['module'] as $module)
                                        <li class="pc-item">
                                            <a class="pc-link" href="{{ $module['path'] }}">{{ $module['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="pc-item">
                                <a href="{{ $val['route'] }}" class="pc-link">
                                    <span class="pc-micon">
                                        {!! $val['icon'] !!}
                                    </span>
                                    <span class="pc-mtext">{{ $val['name'] }}</span>
                                </a>
                            </li>
                    @endif
                @endcan
            @endforeach
        </ul>
    </div>
</div>
</nav>
