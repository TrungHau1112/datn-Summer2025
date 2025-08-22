@props(['id', 'model'])
@can(ucfirst($model) . ' delete')
    <li class="list-inline-item align-bottom {{ $class ?? '' }}" data-bs-toggle="tooltip" title="Khôi phục">
        <a data-id="{{ $id }}" data-model="{{ $model }}" id="restore_tgnt"
            class="avtar avtar-xs btn-link-warning btn-pc-default">
            <i class="ti ti-arrow-back-up f-18"></i>
        </a>
    </li>
@endcan
