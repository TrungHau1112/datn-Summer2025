@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <x-form :config="$config" :model="$category ?? null">
        <div class="row">
            <!-- Cột bên trái chứa các thông tin cơ bản -->
            <div class="col-lg-9 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Thông tin cơ bản
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary" role="alert">
                            <strong>Lưu ý:</strong> <span class="text-danger">(*)</span> là trường bắt buộc nhập
                        </div>
                        <x-input :label="'Tên danh mục'" :name="'name'" :value="$category->name ?? ''" :required="true" />
                    </div>
                </div>
                {{-- SEO --}}
                <x-seo :value_meta_title="$category->meta_title ?? ''" :value_meta_description="$category->meta_description ?? ''" :value_meta_keywords="$category->meta_keywords ?? ''" />
                <!-- Display child categories if we're in edit mode and category exists -->
                @if (isset($category) && isset($config['method']) && $config['method'] === 'edit')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Danh mục con</h5>
                                </div>
                                <div class="card-body">
                                    @if ($category->children && $category->children->count() > 0)
                                        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
                                            @foreach ($category->children as $childCategory)
                                                <div class="card h-100">
                                                    <div
                                                        class="card-body d-flex justify-content-between align-items-center">
                                                        <h6 class="card-title mb-0">{{ $childCategory->name }}</h6>
                                                        <x-edit :id="$childCategory->id" :model="$config['model']" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            Không có danh mục con nào.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <!-- Cột bên phải chứa các thông tin bổ sung -->
            <div class="col-lg-3 col-md-12 mb-4">
                <x-save_back :model="'brand'" />
                <x-thumbnail :label="'Banner'" :name="'thumbnail'" :value="$category->thumbnail ?? 'https://placehold.co/600x600?text=Hinh%20Anh'" :require="true" />
                @if (isset($config['method']) && $config['method'] !== 'edit')
                    <div class="card">
                        <label class="card-header">Loại danh mục</label>
                        <div class="card-body">
                            <div class="form-check">
                                <input checked class="form-check-input category-type" type="radio" name="is_room" id="is_room_yes"
                                    value="1">
                                <label class="form-check-label" for="is_room_yes">Thương hiệu</label>
                            </div>
                            {{-- <div class="form-check">
                                <input class="form-check-input category-type" type="radio" name="is_room" id="is_room_no"
                                    value="2" checked>
                                <label class="form-check-label" for="is_room_no">Danh mục khác</label>
                            </div> --}}
                        </div>
                    </div>
                    <input type="hidden" name="parent_id" value="0" id="default-parent-id">
                    <div class="card" id="parent-category-section">
                        <label for="parent_id" class="card-header">
                            Danh mục cha
                        </label>
                        <div class="card-body">
                            <select name="parent_id" class="form-control js-choice" id="parent-category-select">
                                <option value="0">Không</option>
                                {!! $categoryOptions !!}
                            </select>
                        </div>
                    </div>
                    <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.active')" :value="$category->publish ?? ''"
                        :require="true" />
                @else
                    <div class="card">
                        <label class="card-header">Loại danh mục</label>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input category-type" type="radio" name="is_room" id="is_room_yes"
                                    value="1" {{ $category->is_room == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_room_yes">Thương hiệu</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input category-type" type="radio" name="is_room" id="is_room_no"
                                    value="2" {{ $category->is_room == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_room_no">Danh mục khác</label>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="parent-category-section">
                        <label for="parent_id" class="card-header">
                            Danh mục cha
                        </label>
                        <div class="card-body">
                            <select name="parent_id" class="form-select js-choice" id="parent-category-select">
                                <option value="{{ $category->parent_id }}">
                                    {{ $category->parent ? $category->parent->name : 'Không' }}</option>
                                {!! $categoryOptions !!}
                            </select>
                        </div>
                    </div>
                    <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.active')" :value="$category->publish ?? ''" />
                @endif
            </div>
        </div>
    </x-form>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryTypeInputs = document.querySelectorAll('.category-type');
            const parentCategorySection = document.getElementById('parent-category-section');
            const defaultParentId = document.getElementById('default-parent-id');
            const parentCategorySelect = document.getElementById('parent-category-select');

            // Initial state setup
            function updateParentCategoryVisibility() {
                const isBrand = document.querySelector('.category-type[value="1"]:checked') !== null;

                if (isBrand) {
                    parentCategorySection.style.display = 'none';
                    if (defaultParentId) {
                        defaultParentId.value = '0'; // Set to root category if hidden
                    }
                } else {
                    parentCategorySection.style.display = 'block';
                }
            }

            // Set initial state
            updateParentCategoryVisibility();

            // Add event listeners to radio buttons
            categoryTypeInputs.forEach(input => {
                input.addEventListener('change', updateParentCategoryVisibility);
            });
        });
    </script>
@endsection
