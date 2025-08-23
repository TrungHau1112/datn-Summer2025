@props(['value', 'model'])
<div class="form-check form-switch d-flex justify-content-center custom-switch-v1-{{ $value->id }} js-switch-{{ $value->id }}">
    <input type="checkbox"
        class="form-check-input js-switch status"  id="customSwitch{{ $value->id }}" 
        data-field="publish" data-value="{{ $value->publish }}" data-model="{{ $model }}"
            data-id="{{ $value->id }}" data-publish="{{ $value->publish }}" 
            {{ $value->publish === 1 ? 'checked' : '' }} >
    <label class="form-check-label" for="customSwitch{{ $value->id }}"></label>
</div>
