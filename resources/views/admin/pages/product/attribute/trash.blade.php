@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header text-end">
            <a href="{{ route('attributeCategory.index') }}" class="btn btn-primary">Danh sách danh mục</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên</th>
                            <th class="text-center">Giá trị</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($attributes) && count($attributes))
                            @foreach ($attributes as $key => $attribute)
                                <tr class="animate__animated animate__fadeIn">
                                    <td>{{ $key + 1 }}</td>
                                    <td>
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
                                            <x-restore :id="$attribute->id" :model="ucfirst($config['model'])" />
                                            <x-delete :id="$attribute->id" :model="ucfirst($config['model'])" :destroy="true" />
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
