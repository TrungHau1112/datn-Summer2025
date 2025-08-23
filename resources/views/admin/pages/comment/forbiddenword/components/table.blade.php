@if (isset($forbiddenwords) && count($forbiddenwords))
    @foreach ($forbiddenwords as $key => $forbiddenword)
        <tr>
            <td class="">
                <div class="form-check">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $forbiddenword->id }}" value="{{ $forbiddenword->id }}">
                    <label class="form-check-label" for="ustomCheckbox{{ $forbiddenword->id }}"></label>
                </div>
            </td>
            <td>{{ $key + 1}}</td>
            <td>
                {{-- {{ $forbiddenword->word }} --}}
                <x-quickUpdate :id="$forbiddenword->id" :value="$forbiddenword->word" :model="ucfirst($config['model'])" :name="'word'" />
            </td>
            
            {{-- <td>
                <div class="d-flex flex-wrap">
                    @foreach ($forbiddenword->actions as $action)
                        <span class="badge
                            @if($action == 'delete') bg-danger @else bg-warning @endif
                            fs-6 me-2 mb-2">
                            <i class="bi bi-check-circle"></i>
                            @if($action == 'delete')
                                Xóa bình luận
                            @else
                                Cấm người dùng bình luận 12 giờ
                            @endif
                        </span>
                    @endforeach
                </div>
            </td> --}}
            <td class="text-center table-actions">
                <ul class="list-inline me-auto mb-0">
                    <x-delete :id="$forbiddenword->id" :model="ucfirst($config['model'])" />
                </ul>
            </td>
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="100">
            {{ $forbiddenwords->links('pagination::bootstrap-4') }}
        </td>
    </tr>
@else
<tr>
    <td colspan="9" class="text-center">Không có dữ liệu</td>
</tr>
@endif
