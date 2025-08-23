@props(['label', 'name', 'value'])
<div class="form-group">
    <label for="{{ $name }}" class="control-label text-left">{{ $label }} </label>
    <textarea name="{{ $name }}" id="{{ $name }}" class="ck-editor">{!! old($name, $value ?? '') !!}</textarea>
</div>
