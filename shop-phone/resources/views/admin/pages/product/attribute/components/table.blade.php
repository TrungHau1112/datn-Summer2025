@if (isset($attributes) && count($attributes))
    @foreach ($attributes as $attribute)
        <tr class="animate__animated animate__fadeIn">
            <td class="">
                <div class="form-check">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $attribute->id }}" value="{{ $attribute->id }}">
                    <label class="form-check-label" for="ustomCheckbox{{ $attribute->id }}"></label>
                </div>
            </td>
            <td>
                {{-- @dd($attribute->name) --}}
                {{ $attribute->name }}
            </td>
            <td class="text-center">
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @foreach ($attribute->attributes as $item)
                        <span class="badge bg-light-primary">{{ $item->value }}</span>
                    @endforeach
                </div>
            </td>
            {{-- <td class="text-center">
                                    <x-switchvip :value="$attribute" :model="ucfirst($config['model'])" />
                                </td> --}}
            <td class="text-center table-actions">
                <ul class="list-inline me-auto mb-0">
                    <x-edit :id="$attribute->id" :model="$config['model']" />
                    <x-delete :id="$attribute->id" :model="ucfirst($config['model'])" />
                </ul>
            </td>
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="100">
            {!! $attributes->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
