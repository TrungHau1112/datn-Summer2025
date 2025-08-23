@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <x-form :config="$config" :model="$post ?? null">
        <div class="row">
            <!-- thông tin cơ bản -->
            <div class="col-lg-9 col-md-12 mb-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                Thông tin cơ bản
                            </div>
                            <div class="card-body">
                                <div class="alert alert-primary" role="alert">
                                    <strong>Lưu ý:</strong> <span class="text-danger">(*)</span> là trường bắt buộc nhập
                                </div>
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}
                                <div class="mb-3">
                                    <x-input :label="'Tên bài viết'" :name="'title'" :class="'name-post'" :value="$post->title ?? old('title')"
                                        :required="true" />
                                </div>

                                <div class="form-group mb-3">
                                    <label for="excerpt">Mô tả ngắn</label>
                                    <textarea class="form-control" name="excerpt" id="excerpt" rows="3">{{ $post->excerpt ?? old('excerpt') }}</textarea>
                                </div>

                                <x-editor :label="'Nội dung bài viết'" :name="'content'" :value="$post->content ?? old('content')" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <x-seo :value_meta_title="$post->meta_title ?? old('meta_title')" :value_meta_description="$post->meta_description ?? old('meta_description')" :value_slug="$post->slug ?? old('slug')" />
                    </div>
                </div>
            </div>

            <!-- thông tin bổ sung -->
            <div class="col-lg-3 col-md-12 mb-4">
                <x-save_back :model="$config['model']" />
                <x-thumbnail :label="'Ảnh bài viết'" :name="'thumbnail'" :value="$post->thumbnail ?? 'https://placehold.co/600x600?text=Image'" />
                {{-- <div class="card">
                    <div class="card-header">
                        Bài viết nổi bật
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="is_featured" value="1"
                                id="is_featured2"
                                {{ old('is_featured', $post->is_featured ?? 2) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured2">Bài viết nổi bật</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="is_featured" value="2"
                                id="is_featured1"
                                {{ old('is_featured', $post->is_featured ?? 2) == 2 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured1">Bài viết không nổi bật</label>
                        </div>
                    </div>
                </div> --}}
                
                <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.publish')" :value="$post->publish ?? old('publish')" />
            </div>
        </div>
    </x-form>
@endsection
