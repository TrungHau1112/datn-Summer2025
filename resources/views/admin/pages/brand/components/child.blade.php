<tr>
    <td>
        <div class="form-check">
            <input class="form-check-input input-primary input-checkbox checkbox-item"
                type="checkbox" id="customCheckbox{{ $child->id }}"
                value="{{ $child->id }}">
            <label class="form-check-label"
                for="ustomCheckbox{{ $child->id }}"></label>
        </div>
    </td>
    <td>{{ $child->id }}</td>
    <td>
        <a data-fancybox="gallery" href="{{ $child->thumbnail }}">
            <img loading="lazy" width="50" class="rounded" src="{{ $child->thumbnail }}" alt="{{ $child->name }}">
        </a>
    </td>
    <td>
        <span class="row-name">{{ $char }}{{ $child->name }}</span>
    </td>
    <td>{{ changeDateFormat($child->created_at) }}</td>
    <td class="text-center">
        <x-switchvip :value="$child" :model="ucfirst($config['model'])" />
    </td>
    <td class="text-center table-actions">
        <ul class="list-inline me-auto mb-0">
            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                title="Chỉnh sửa">
                <a href="{{ route('category.edit', ['id' => $child->id, 'page' => request()->get('page', 1)]) }}"
                    class="avtar avtar-xs btn-link-success btn-pc-default">
                    <i class="ti ti-edit-circle f-18"></i>
                </a>
            </li>
            <x-delete :id="$child->id" :model="ucfirst($config['model'])"/>
        </ul>
    </td>
</tr>
@foreach($child->children as $child)
    @include('admin.pages.category.components.child', ['char' => $char .' |-- '])
@endforeach
