@props(['breadcrumb'])

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Bảng điều khiển</a></li>
                    @foreach ($breadcrumb['list'] as $key => $val)
                        @if ($key == count($breadcrumb['list']) - 1)
                            <li class="breadcrumb-item active" aria-current="page">{{ $val }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $val }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $breadcrumb['name'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
