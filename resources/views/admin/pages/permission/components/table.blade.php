@if (isset($permissions) && count($permissions))
    @foreach ($permissions as $permission)
        <tr class="animate__animated animate__fadeIn">
            <td>
                <x-quickUpdate :id="$permission->id" :value="$permission->description" :model="ucfirst($config['model'])" :name="'description'" />
            </td>
            <td>
                <span class="row-name">{{ $permission->name }}</span>
            </td>
            @foreach ($roles as $role)
                <td class="text-center " data-axis="{{ $role->id }}">
                    <div class="form-check d-flex justify-content-center">
                        <input class="permission_to_role form-check-input input-primary input-checkbox checkbox-item"
                            type="checkbox" data-roleId="{{ $role->id }}" data-permissionName="{{ $permission->name }}"
                            id="permission_role{{ $permission->id }}{{ $role->id }}"
                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                            {{ $role->name == 'Super Admin' ? 'checked disabled' : '' }}>
                        <label
                            class="form-check-label"for="permission_role{{ $permission->id }}{{ $role->id }}"></label>
                    </div>
                </td>
            @endforeach
            @if ($roles->count() < 5)
                <td>
                </td>
            @endif
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="100">
            {!! $permissions->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
