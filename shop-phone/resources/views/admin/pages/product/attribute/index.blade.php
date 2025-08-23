@extends('admin.layout')
@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header">
            <x-filter :model="$config['model']" :createButton="[
                'label' => '',
                'route' => $config['model'] . '.create',
            ]" :options="[
                // 'actions' => generateSelect('Hành động', __('general.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                // 'publish' => generateSelect('Trạng thái', __('general.publish')),
                'sort' => generateSelect('Sắp xếp', __('general.sort')),
            ]" />
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>Tên</th>
                            <th class="text-center">Giá trị </th>
                            {{-- <th class="text-center">Trạng thái</th> --}}
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @include('admin.pages.product.attribute.components.table')
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">
            {{ $attributes->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
