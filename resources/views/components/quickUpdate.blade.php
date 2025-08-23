@props(['id', 'value', 'name', 'model'])
<button class="quick_update btn btn-link text-start" data-bs-toggle="tooltip" title="Nhấn đúp để chỉnh sửa"
    data-input-id="textInput{{ $id }}" data-id="{{ $id }}" data-model="{{ $model }}"
    name="{{ $name }}" style="width: 300px">
    {{ $value ?? '...' }}
</button>
<input class="form-control hidden" type="text" id="textInput{{ $id }}" value="{{ $value }}"
    placeholder="..." style="width: 300px">
