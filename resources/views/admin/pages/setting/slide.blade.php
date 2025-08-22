@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <form action="{{ route('setting.sliderUpdate') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header">
                        Thông tin chung
                    </div>
                    <div class="card-body">
                        <div class="text-end mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="image img-cover image-target">
                                        <img src="{{ old('image', 'https://placehold.co/600x600?text=click để \n thêm ảnh') }}" width="100" class="img-thumbnail img-fluid" alt="Hình ảnh">
                                    </div>
                                <input type="hidden" name="image" value="{{ old('image', 'https://placehold.co/600x600?text=click để thêm ảnh') }}">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Banner</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="slide-list">
                                    @if ($slides->isEmpty())
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Hiện tại chưa có banner nào.</td>
                                        </tr>
                                    @else
                                        @foreach ($slides as $slide)
                                            <tr class="slide-item">
                                                <td>
                                                    <img src="{{ $slide->image }}" alt="" width="100" class="img-thumbnail img-fluid">
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('setting.sliderDelete', $slide->id) }}" class="btn btn-outline-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <i class="ti ti-trash me-1"></i> Xóa
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-3">
                <x-save_back :model="'setting'" />
                {{-- <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.active')" :value="$user->publish ?? ''" /> --}}
            </div>

        </div>
        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}" />
    </form>
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <script>
        let collections = @json($collections ?? []);
    </script>
@endsection
