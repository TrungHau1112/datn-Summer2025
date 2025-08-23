@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <div class="card">
        <div class="card-header text-end">
            <a href="{{ route('post.index') }}" class="btn btn-primary">Danh sách bài viết</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Tóm tắt</th>
                            <th>Người tạo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($posts) && count($posts))
                            @foreach ($posts as $key => $post)
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <a data-fancybox="gallery" href="{{ $post->thumbnail }}">
                                            <img loading="lazy" width="80" class="rounded" src="{{ $post->thumbnail }}"
                                                alt="{{ $post->title }}">
                                        </a>
                                    </td>

                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->excerpt }}</td>
                                    <td>{{ $post->user->name }}</td>
                                    <td class="text-center table-actions">
                                        <ul class="list-inline me-auto mb-0">
                                            <x-edit :id="$post->id" :model="$config['model']" />
                                            <x-restore :id="$post->id" :model="ucfirst($config['model'])" />
                                            <x-delete :id="$post->id" :model="ucfirst($config['model'])" :destroy="true" />
                                           
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