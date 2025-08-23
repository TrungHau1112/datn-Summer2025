@props(['id', 'model', 'deleteAxis', 'route', 'class', 'destroy' => false])
@can(ucfirst($model) . ' delete')
    <li class="list-inline-item align-bottom {{ $class ?? '' }}" data-bs-toggle="tooltip" title="Xóa">
        <a data-id="{{ $id }}" data-route="{{ $route ?? '' }}" data-model="{{ $model }}"
            data-destroy="{{ $destroy }}" data-axis="{{ $deleteAxis ?? 'row' }}" id="delete_tgnt"
            class="avtar avtar-xs btn-link-danger btn-pc-default">
            <i class="ti ti-trash f-18"></i>
        </a>
    </li>
@endcan
