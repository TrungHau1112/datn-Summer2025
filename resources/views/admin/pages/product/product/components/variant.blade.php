<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <label for="" class="d-flex align-items-center mb-0" style="gap: 10px">
                Sản phẩm có nhiều phiên bản
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input js-switch turnOnVariant" value="1"
                        name="has_attribute" id="customSwitch" {{ old('has_attribute') == 1 || (isset($product) && count($product->productVariants) > 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="customSwitch"></label>
                </div>
            </label>
        </div>
        <div class="card-body">
            <div class="form-group mb-0">
                <div class="attribute_container_product">
                    <div class="form-group">
                        <div class="alert alert-primary">
                            <strong class="text-danger">*</strong> Cho phép bạn tạo nhiều phiên bản sản phẩm với các
                            thuộc tính khác nhau
                        </div>
                        @php
                            $variantCatalogue = old(
                                'attributeCatalogue',
                                isset($product->attribute_category)
                                ? json_decode($product->attribute_category, true)
                                : '',
                            );
                        @endphp
                        <div
                            class="variant-wrapper {{ old('has_attribute') == 1 || $variantCatalogue != '' ? '' : 'hidden' }}">
                            <div class="variant-container row">
                                {{-- <div class="col-3">
                                    <p class="mb-2 text-primary">Chọn thuộc tính</p>
                                </div>
                                <div class="col-3">
                                    <p class="mb-2 text-primary">Chọn giá trị của thuộc tính</p>
                                </div> --}}
                            </div>
                            <div class="variant-body mb-3">
                                @if ($variantCatalogue && count($variantCatalogue) > 0)
                                    @foreach ($variantCatalogue as $keyAttr => $valAttr)
                                        <div class="row mb-3 variant-item">
                                            <div class="col-lg-3">
                                                <div class="attribute-catalogue">
                                                    <select name="attributeCatalogue[]" id=""
                                                        class="choose-attribute niceSelect">
                                                        <option value="">Chọn Nhóm thuộc tính</option>
                                                        @foreach ($attributes as $item)
                                                            <option {{ $valAttr == $item->id ? 'selected' : '' }}
                                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">

                                                <select class="selectVariant variant-{{ $valAttr }} form-control"
                                                    name="attributeValue[{{ $valAttr }}][]" multiple
                                                    data-catid="{{ $valAttr }}">
                                                </select>

                                                {{-- <input type="text" name="" disabled
                                                    class="fake-variant h-100 form-control"> --}}
                                            </div>
                                            <div class="col-lg-1">
                                                <button type="button"
                                                    class="h-100 w-100 remove-attribute btn btn-icon btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="variant-foot">
                                <div class="parent-add-variant">
                                    <button type="button" class="btn btn-primary add-variant">
                                        Thêm phiên bản mới
                                    </button>
                                </div>
                            </div>
                            <div class="card product-variant mt-3">
                                <div class="card-header">
                                    Danh sách phiên bản sản phẩm
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive ">
                                        <table class="table variantTable mb-0">
                                            <thead></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    var attributeCatalogue = @json(
        $attributes->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        })->values()
    );
    var attributeValue =
        `{{ base64_encode(json_encode(old('attributeValue') ?? (isset($product->attribute) ? $product->attribute : []))) }}`;
    var variant =
        `{{ base64_encode(json_encode(old('variant') ?? (isset($product->variant) ? json_decode($product->variant, true) : []))) }}`;
</script>
{{-- dd($attributes); --}}
<style>
    .select2.select2-container.select2-container--default {
        height: 100% !important;
        width: 100% !important;
    }

    .select2-selection.select2-selection--multiple {
        height: 100% !important;
    }
</style>