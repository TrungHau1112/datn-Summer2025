@props(['id', 'options', 'name', 'model'])

<select name="{{ $name }}" id="{{ $id }}" data-model="{{ $model }}">
    @if (is_array($options))
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
        @endforeach
    @else
        <option value="">Invalid options</option>
    @endif
</select>
