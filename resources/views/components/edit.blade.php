@props(['id', 'model', 'deleteAxis', 'route', 'class'])
@can(ucfirst($model) . ' edit')
    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Chỉnh sửa">
        <a data-id="{{ $id }}" data-route="{{ $route ?? '' }}" data-model="{{ $model }}"
            data-axis="{{ $deleteAxis ?? 'row' }}"
            {{-- href="{{ route($model . '.edit', ['id' => $id, 'page' => request()->get('page', 1)]) }}" --}}
            href="{{ route($model . '.edit', ['id' => $id]) }}"
            class="avtar avtar-xs btn-link-success btn-pc-default">
            <i class="ti ti-edit-circle f-18"></i>
        </a>
    </li>
@endcan
