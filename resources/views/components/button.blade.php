@props(['label', 'icon', 'class'])

<button type="submit" name="send" value="send" class="btn {{ $class }} text-13 flex flex-middle">
    <i data-feather="save" class="feather-icon"></i>
    {{ $label }}
</button>