@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />

    <x-form :config="$config" :model="$discountCode ?? null">
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
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <x-input :label="'Mã giảm giá'" :name="'code'" :value="$discountCode->code ?? ''" :required="true" />
                            </div>
                            <div class="col-lg-6">
                                <x-input :label="'Tiêu đề mã'" :name="'title'" :value="$discountCode->title ?? ''" :required="true" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <x-input :type="'date'" :label="'Ngày bắt đầu'" :name="'start_date'" :value="$discountCode->start_date ?? ''"
                                    :required="true" />
                            </div>
                            <div class="col-lg-6">
                                <x-input :type="'date'" :label="'Ngày kết thúc'" :name="'end_date'" :value="$discountCode->end_date ?? ''"
                                    :required="true" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <label for="discount_type">Chọn mã giảm giá theo <span class="text-danger">*</span></label>
                                <select class="form-control js-choice-multiple" name="discount_type" id="discount_type">
                                    <option value="1"
                                        {{ old('discount_type', $discountCode->discount_type ?? '') == '1' ? 'selected' : '' }}>
                                        Theo %
                                    </option>
                                    <option value="2"
                                        {{ old('discount_type', $discountCode->discount_type ?? '') == '2' ? 'selected' : '' }}>
                                        Theo tiền
                                    </option>
                                </select>
                                @error('discount_type')
                                    <small class="error text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <x-input :type="'number'" :class="'type_discount_money'" :label="'Giảm bao nhiêu'" :name="'discount_value'"
                                    :value="$discountCode->discount_value ?? old('discount_value')" :required="true" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <x-input :class="'int'" :label="'Giá tối thiểu để áp dụng mã giảm giá'" :name="'min_order_amount'" :value="$discountCode->min_order_amount ?? old('min_order_amount')"
                                    :required="true" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <x-save_back :model="$config['model']" />
                <x-publish :label="'Trạng thái'" :name="'publish'" :option="__('general.active')" :value="$discountCode->status ?? ''" />
            </div>
        </div>
        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}" />
    </x-form>
    <script>
        $('#discount_type').on('change', function() {
            if ($(this).val() == 1) {
                $('.type_discount_money').attr('max', 100).removeClass('int').attr('type', 'number');
            } else {
                $('.type_discount_money').attr('max', 1000000000).addClass('int').attr('type', 'text');
            }
        })

        $(document).ready(function() {
            if ($('#discount_type').val() == 1) {
                $('.type_discount_money').attr('max', 50);
                $('.type_discount_money').attr('min', 1);
            } else {
                $('.type_discount_money').attr('max', 1000000000);
                $('.type_discount_money').attr('min', 1).addClass('int').attr('type', 'number');

            }
        })
    </script>
@endsection
