@props(['name', 'options', 'root', 'class', 'label', 'value', 'required' , 'data'])

<div class="form-group">
    <label for="{{ $name }}" class="control-label">
        {{ $label ?? ''}} {!! $required == 'true' ? '<span class="text-danger">*</span>' : '' !!}
    </label>
    <select id="{{ $name }}" data-id="{{$data->id ?? ''}}" name="{{ $name }}"  class="form-control {{ $class ?? '' }} select2 @error($name)is-invalid @enderror">
        <option value="0">{{ $root }}</option>
        @foreach($options as $key => $val)
        <option {{ $val['id'] == old($name, $value ?? 0) ? 'selected' : '' }} value="{{ $val['id'] }}">{{ $val['name'] }}</option>
        @endforeach
    </select>
    
    @error($name)
        <small class="error text-danger">{{ $message }}</small>
    @enderror
</div>