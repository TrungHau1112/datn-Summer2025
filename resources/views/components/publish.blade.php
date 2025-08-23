@props(['label', 'name', 'value', 'option', 'required'])
<div class="card">
    <div class="card-header">
        {{ $label }} @if ($required ?? '')
            <span class="text-danger">*</span>
        @endif
    </div>
    <div class="card-body">
        @foreach ($option as $key => $items)
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="{{ $name }}"
                value="{{ $items['id'] }}" {{ $items['id'] == 1 || $value == $items['id'] ? 'checked' : '' }}
                id="{{ $name . $key }}" name="{{ $name }}" >
            <label class="form-check-label" for="{{ $name . $key }}" >{{ $items['name'] }}</label>
        </div>
        @endforeach
        @error($name)
            <small class="error text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

