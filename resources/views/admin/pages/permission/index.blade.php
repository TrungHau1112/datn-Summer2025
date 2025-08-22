@extends('admin.layout')

@section('template')

    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />

    <div class="card">
        <div class="card-header">
            <x-filter :model="$config['model']" :createButton="[
                'label' => 'Làm mới',
                'method' => 'post',
                'route' => $config['model'] . '.store',
            ]" :options="[
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                'sort' => generateSelect('Sắp xếp', __('general.sort')),
            ]" />
        </div>
        <div class="card-body p-0">
            <div class="table-">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mô tả</th>
                            <th>Quyền hạn</th>
                            @foreach ($roles as $role)
                                <th class="text-center position-relative" data-axis="{{ $role->id }}">
                                    <div class="delete-custom">
                                        <a class="text-primary" href="#">{{ $role->name }}</i></a>
                                        <div class="delete-button">
                                            <ul class="list-inline me-auto mb-0">
                                                @if ($role->name !== 'Super Admin')
                                                    @can('Permission edit')
                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                            <a href="{{ route('role.edit', $role->id) }}"
                                                                class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    <x-delete :id="$role->id" :model="'Role'" :deleteAxis="'column'" />
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                            @if ($roles->count() < 5)
                                <th>
                                    <a href="{{ route('role.create') }}"><i class="fa fa-plus"></i></a>
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @include('admin.pages.permission.components.table')
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">

@endsection
