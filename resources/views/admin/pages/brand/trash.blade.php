@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header text-end">
            <a href="{{ route('brand.index') }}" class="btn btn-primary">Danh sách danh mục</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Hình ảnh</th>
                            <th>Tên danh mục</th>
                            <th>Loại danh mục</th>
                            <th>Ngày tạo</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($categories) && count($categories))
                            @foreach ($categories as $key => $category)
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <a data-fancybox="gallery" href="{{ $category->thumbnail }}">
                                            <img loading="lazy" width="50" class="rounded"
                                                src="{{ $category->thumbnail }}" alt="{{ $category->name }}">
                                        </a>
                                    </td>
                                    <td>
                                        <span class="row-name">{{ $category->name }}</span>
                                    </td>

                                    @if ($category->is_room == 1)
                                        <td>
                                            <span class="badge bg-light-primary">Phòng</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge bg-light-danger">Danh mục khác</span>
                                        </td>
                                    @endif
                                    <td>{{ changeDateFormat($category->created_at) }}</td>
                                    <td class="text-center">
                                        <x-switchvip :value="$category" :model="ucfirst($config['model'])" />
                                    </td>
                                    <td class="text-center table-actions">
                                        <ul class="list-inline me-auto mb-0">
                                            <x-edit :id="$category->id" :model="'category'" />
                                            <x-restore :id="$category->id" :model="'Category'" />
                                            <x-delete :id="$category->id" :model="'Category'" :destroy="true" />
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="100" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
