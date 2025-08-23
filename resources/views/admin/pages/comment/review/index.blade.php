@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />

    <div class="card">
        <div class="card-header">
            <x-filter :model="$config['model']" :options="[
                'actions' => generateSelect('Hành động', __('general.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                'sort' => generateSelect('Sắp xếp', __('general.sort')),
                'rating' => generateSelect('Đánh giá', __('general.rating')),
            ]" />
        </div>  
        <div class="card-body p-0">
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
                            <th>STT</th>
                            <th>Ảnh đại diện</th>
                            <th>Tên người dùng</th>
                            <th>Nội dung đánh giá</th>
                            <th>Đánh giá</th>
                            <th>Sản phẩm</th>
                            <th>Ngày đánh giá</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">
            {{ $reviews->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
