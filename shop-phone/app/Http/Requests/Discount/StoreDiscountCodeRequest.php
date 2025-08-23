<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|unique:discount_codes,code',
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'discount_type' => 'required|in:1,2',
            'publish' => 'integer',
            'min_order_amount' => 'required',
            'discount_value' => 'required'

        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề mã giảm giá',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu phải là một ngày hợp lệ.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.date' => 'Ngày kết thúc phải là một ngày hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'discount_type.required' => 'Vui lòng chọn loại mã giảm giá',
            'discount_type.in' => 'Loại mã giảm giá phải là 1 hoặc 2.',
            'publish.integer' => 'Trạng thái xuất bản phải là một số nguyên.',
            'min_order_amount.required' => 'Số tiền đơn hàng tối thiểu là bắt buộc.',
            'discount_value.required' => 'Vui lòng nhập giá tiền'
        ];
    }
}
