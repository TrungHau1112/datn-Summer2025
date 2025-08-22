@extends('admin.layout')

@section('template')

    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />

    {{-- <div class="card">
        <div class="card-header">
            <x-filter :model="$config['model']" :createButton="[
                'label' => '',
                'route' => $config['model'] . '.create',
            ]" :options="[
                'actions' => generateSelect('Hành động', __('general.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                'sort' => generateSelect('Sắp xếp', __('general.sort')),
            ]" />
        </div> --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            {{-- <th>
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th> --}}
                            <th>STT</th>
                            <th>Ảnh đại diện</th>
                            <th>Tên người dùng</th>
                            <th>Nội dung bình luận</th>
                            <th>Bộ sưu tập/ Bài viết</th>
                            <th>Ngày bình luận</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($replies) && count($replies))
                            @foreach ($replies as $key => $reply)
                                <tr class="animate__animated animate__fadeInDown animate__faster">
                                    {{-- <td class="">
                                        <div class="form-check">
                                            <input class="form-check-input input-primary input-checkbox checkbox-item"
                                                type="checkbox" id="customCheckbox{{ $reply->id }}"
                                                value="{{ $reply->id }}">
                                            <label class="form-check-label" for="ustomCheckbox{{ $reply->id }}"></label>
                                        </div>
                                    </td> --}}
                                    <td>{{  $key + 1}}</td>
                                    <td>
                                        <a href="{{ $reply->user->avatar }}" data-fancybox="gallery">
                                            <img loading="lazy" width="50" class="rounded"
                                                src="{{ $reply->user->avatar }}" alt="{{ $reply->user->name }}">
                                        </a>
                                    </td>
                                    <td>
                                        {{ $reply->user->name }}
                                    </td>

                                    <td>
                                        <span class="row-name">{{ $reply->content }}</span>
                                    </td>
                                    <td>{{ $reply->collection->name }}</td>
                                    <td>{{ changeDateFormat($reply->created_at) }}</td>
                                    <td class="text-center table-actions">
                                        <ul class="list-inline me-auto mb-0">
                                            <x-delete :id="$reply->id" :model="ucfirst($config['model'])" />
                                        </ul>
                                    </td>
                                </tr>
                                @foreach ($reply->replies->whereNotNull('parent_id') as $reply)
                                    @include('admin.pages.comment.components.comment', [
                                        'reply' => $reply,
                                        'char' => html_entity_decode('&#9472;&#9472;&#9472;'),
                                    ])
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">
            {{-- {{ $categorys->links('pagination::bootstrap-4') }} --}}
        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">

@endsection
