<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>{{ $breadcrumb['name'] }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard.index') }}">Home</a>
            </li>
            @foreach($breadcrumb['list'] as $key => $val)
            <li>
                {{ $val }}
            </li>
            @endforeach
        </ol>
    </div>
</div>