@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header text-end">
            <a href="{{ route('collection.index') }}" class="btn btn-primary">Danh sách danh mục</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ảnh bìa</th>
                            <th>Tên bộ sưu tập</th>
                            <th>Mô tả</th>
                            <th class="text-center">Ngày tạo</th>
                            <th class="text-center">Ngày cập nhật</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($collections) && count($collections))
                            @foreach ($collections as $key => $collection)
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="">
                                        <div class="form-check">
                                            <input class="form-check-input input-primary input-checkbox checkbox-item"
                                                type="checkbox" id="customCheckbox{{ $collection->id }}"
                                                value="{{ $collection->id }}">
                                            <label class="form-check-label"
                                                for="ustomCheckbox{{ $collection->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a data-fancybox="gallery" href="{{ $collection->thumbnail }}">
                                            <img decoding="async" width="80" class="rounded"
                                                src="{{ $collection->thumbnail }}" alt="{{ $collection->name }}">
                                        </a>
                                    </td>

                                    <td class="text-primary">
                                        <span class="webkit-box-1">{{ $collection->name }}</span>
                                        {!! $collection->discount != 0
                                            ? "<span class='badge bg-light-danger'>Có giảm giá - $collection->discount%</span>"
                                            : '' !!}
                                    </td>
                                    <td>
                                        <span class="webkit-box-1">{{ $collection->short_description }}</span>
                                    </td>
                                    <td class="text-wrap">
                                        <span class=""
                                            style="white-space: nowrap ;">{{ changeDateFormat($collection->created_at) }}</span>
                                    </td>
                                    <td class="text-wrap">
                                        <span class=""
                                            style="white-space: nowrap ;">{{ changeDateFormat($collection->updated_at) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <x-switchvip :value="$collection" :model="ucfirst($config['model'])" />
                                    </td>
                                    <td class="text-center table-actions">
                                        <ul class="list-inline me-auto mb-0">
                                            <x-edit :id="$collection->id" :model="$config['model']" />
                                            <x-restore :id="$collection->id" :model="ucfirst($config['model'])" />
                                            <x-delete :id="$collection->id" :model="ucfirst($config['model'])" :destroy="true" />
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
