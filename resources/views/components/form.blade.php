@props(['config', 'model'])

@php
    $action = ($config['method'] === 'edit') ? route( $config['model'].'.update', ['id' => $model->id]) : route($config['model'].'.store')
@endphp


<form method="post" action="{{ $action }}">
    @csrf
    @if(isset($config['method']) && $config['method'] === 'edit')
        @method('PUT')
    @endif

    {{ $slot }}

</form>