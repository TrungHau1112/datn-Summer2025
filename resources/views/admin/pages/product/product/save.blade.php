@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <x-form :config="$config" :model="$product ?? null">
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
                                    <x-input :label="'Tên sản phẩm'" :name="'name'" :class="'name-product'" :value="$product->name ?? old('name')"
                                        :required="true" />
                                </div>

                                <div class="form-group mb-3">
                                    <label for="short_content">Mô tả ngắn</label>
                                    <textarea class="form-control" name="short_content" id="short_content" rows="3">{{ $product->short_content ?? old('short_content') }}</textarea>
                                </div>
                                {{--  --}}
                                <div class="row price-group">
                                    <div class="col-lg-3">
                                        <div class="mb-3 form-group">
                                            <label class="form-label" for="sku">SKU <span
                                                    class="text-danger">*</span></label>
                                            <input disabled required class="form-control" type="text" id="sku"
                                                value="{{ $product->sku ?? old('sku', $sku) }}">
                                            <input class="form-control" type="hidden" name="sku" id="sku"
                                                value="{{ $product->sku ?? old('sku', $sku) }}">
                                            @error('sku')
                                                <small class="error text-danger">* {{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3 form-group">
                                            <label class="form-label" for="quantity">Số lượng <span
                                                    class="text-danger">*</span></label>
                                            <input required class="form-control int"
                                                value="{{ old('quantity', $product->quantity ?? '') }}" type="text"
                                                name="quantity" id="quantity" value="">
                                            @error('quantity')
                                                <small class="error text-danger">* {{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3 form-group">
                                            <label class="form-label" for="price">Giá tiền <span
                                                    class="text-danger">*</span></label>
                                            <input required class="form-control int"
                                                value="{{ old('price', formatNumber($product->price ?? '')) }}"
                                                type="text" name="price" id="price" value="">
                                            @error('price')
                                                <small class="error text-danger">* {{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3 form-group">
                                            <label class="form-label" for="discount">Giảm giá (1-50%) </label>
                                            <input class="form-control int"
                                                value="{{ $product->discount ?? old('discount') }}" type="text"
                                                name="discount" id="discount">
                                            @error('discount')
                                                <small class="error text-danger">* {{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{--  --}}
                                <x-album :label="'Hình ảnh sản phẩm'" :name="'albums'" :value="old(
                                    'albums',
                                    isset($product->albums) ? json_decode($product->albums) : null,
                                )" />

                                <x-editor :label="'Mô tả sản phẩm'" :name="'description'" :value="$product->description ?? old('description')" class="form-control" />
                            </div>
                        </div>
                    </div>

                    @include('admin.pages.product.product.components.variant')

                    <div class="col-lg-12">
                        <x-seo :value_meta_title="$product->meta_title ?? old('meta_title')" :value_meta_description="$product->meta_description ?? old('meta_description')" :value_slug="$product->slug ?? old('slug')" />
                    </div>
                </div>
            </div>

            <!-- thông tin bổ sung -->
            <div class="col-lg-3 col-md-12 mb-4">
                <x-save_back :model="$config['model']" />
                <x-thumbnail :label="'Ảnh sản phẩm'" :name="'thumbnail'" :value="$product->thumbnail ?? 'https://placehold.co/600x600?text=The Gioi \nNoi That'" />
                <div class="card">
                    <div class="card-header">
                        Chọn danh mục
                    </div>
                    <div class="card-body">
                        <select required name="category" class="js-choice form-control @error('category') is-invalid @enderror"
                            id="">
                            <option value="" disabled selected>Chọn danh mục</option>
                            @foreach ($categories as $category)
                                @if ($category->is_room == 2)
                                    <option value="{{ $category->id }}" @if (old('category') == $category->id || (isset($product) && $product->categories->pluck('id')->contains($category->id))) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        @error('category')
                            <small class="error text-danger">* {{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Chọn danh mục phòng
                    </div>
                    <div class="card-body">
                        <select required name="category_id[]"
                            class=" form-control @error('category_id') is-invalid @enderror">
                            <option value="" disabled selected> Chọn brand</option>
                            @foreach ($categories as $category)
                                @if ($category->is_room == 1)
                                    <option value="{{ $category->id }}" @if (in_array($category->id, old('category_id', [])) ||
                                            (isset($product) && $product->categories->pluck('id')->contains($category->id))) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')
                            <small class="error text-danger">* {{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Sản phẩm nổi bật
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="is_featured" value="1"
                                id="is_featured2"
                                {{ old('is_featured', $product->is_featured ?? 2) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured2">Sản phẩm nổi bật</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="is_featured" value="2"
                                id="is_featured1"
                                {{ old('is_featured', $product->is_featured ?? 2) == 2 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured1">Sản phẩm không nổi bật</label>
                        </div>
                    </div>
                </div>
                
                <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.publish')" :value="$product->publish ?? old('publish')" />
            </div>
        </div>
    </x-form>
@endsection
