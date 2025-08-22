@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <x-form :config="$config" :model="$attribute ?? null">
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header">
                        Thông tin chung
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary" role="alert">
                            <strong>Lưu ý:</strong> <span class="text-danger">(*)</span> là trường bắt buộc nhập
                        </div>

                        <x-input :label="'Tên thuộc tính'" :name="'name'" :value="$attribute->name ?? ''" :require="true" />

                        {{-- thêm value biến thể --}}
                        <Label class="form-label">Giá trị của thuộc tính</Label>
                        <div class="attribute_value_container">
                            @isset($attribute->attributes)
                                @foreach ($attribute->attributes as $item)
                                    {{-- <div class="input-group animate__animated animate__fadeInDown animate__faster mb-3">
                                        <input type="text" value="{{ $item->value }}"
                                            name="attribute_value[{{ $item->id }}][]" class="form-control attribute_input">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-danger remove_attribute_value"
                                                style="cursor: pointer"><i class="fas fa-trash"></i></span>
                                        </div>
                                    </div> --}}
                                    <div class="input-group animate__animated animate__fadeInDown animate__faster mb-3">
                                        <input type="text" class="form-control attribute_input" placeholder="Right Button"
                                            name="attribute_value[{{ $item->id }}][]" value="{{ $item->value }}">
                                        <button class="btn btn-outline-danger remove_attribute_value" type="button"><i
                                                class="ti ti-trash"></i></button>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                        <button class="btn btn-primary add_attribute_value mb-3" type="button">
                            <i class="fa fa-plus"></i>
                            Thêm giá trị
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <x-save_back :model="$config['model']" />
                <div class="hidden">
                    <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.publish')" :value="$attribute->publish ?? old('publish')" />
                </div>
            </div>
        </div>
    </x-form>

    <script>
        let attribute_values = @json(old('attribute_value', []));
    </script>
@endsection
