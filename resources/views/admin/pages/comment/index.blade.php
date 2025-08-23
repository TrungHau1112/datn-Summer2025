@extends('admin.layout')

@section('template')

    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />

    <div class="card">
        <div class="card-header">
            <x-filter
            :model="$config['model']"
            :options="[
                'actions' => generateSelect('Hành động', __('general.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                'sort' => generateSelect('Sắp xếp', __('general.commemt_sort')),
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
                        <th>Nội dung bình luận</th>
                        <th>Bộ sưu tập/ Bài viết</th>
                        <th>Ngày bình luận</th>
                        <th>Trả lời</th>
                        <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">

        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">

@endsection
