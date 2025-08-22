@if (isset($categories) && count($categories))
    @foreach ($categories as $key => $category)
        @if ($category->parent_id == null)
            <tr class="animate__animated animate__fadeIn">
                <td class="">
                    <div class="form-check">
                        <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                            id="customCheckbox{{ $category->id }}" value="{{ $category->id }}">
                        <label class="form-check-label" for="ustomCheckbox{{ $category->id }}"></label>
                    </div>
                </td>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a data-fancybox="gallery" href="{{ $category->thumbnail }}">
                        <img loading="lazy" width="50" class="rounded" src="{{ $category->thumbnail }}"
                            alt="{{ $category->name }}">
                    </a>
                </td>
                <td>
                    <span class="row-name">{{ $category->name }}</span>
                </td>

                {{-- @if ($category->is_room == 1)
                    <td>
                        <span class="badge bg-light-primary">Phòng</span>
                    </td>
                @else
                    <td>
                        <span class="badge bg-light-danger">Danh mục khác</span>
                    </td>
                @endif --}}
                <td>{{ changeDateFormat($category->created_at) }}</td>
                <td class="text-center">
                    <x-switchvip :value="$category" :model="ucfirst($config['model'])" />
                </td>

                <td class="text-center table-actions">
                    <ul class="list-inline me-auto mb-0">
                        <x-edit :id="$category->id" :model="$config['model']" />
                        <x-delete :id="$category->id" :model="ucfirst($config['model'])" />
                    </ul>
                </td>
            </tr>
            @foreach ($category->children as $child)
                @include('admin.pages.category.components.child', [
                    'child' => $child,
                    'char' => ' |-- ',
                ])
            @endforeach
        @endif
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="100">
            {!! $categories->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
