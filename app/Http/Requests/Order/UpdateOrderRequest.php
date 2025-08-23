<?php

namespace App\Http\Requests\order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'nullable|string|max:255',
            'payment_status.*' => 'required',
            'address' => 'required|string|max:255',
            'status.*' => 'required',
            'quantity.*' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.string' => 'Phương thức thanh toán phải là chuỗi ký tự.',
            'payment_method.max' => 'Phương thức thanh toán không được vượt quá 255 ký tự.',
            'payment_status.*.required' => 'Trạng thái thanh toán là bắt buộc.',
            'address.required' => 'Địa chỉ không được để trống.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'status.*.required' => 'Trạng thái đơn hàng là bắt buộc.',
            'quantity.*.required' => 'Số lượng là bắt buộc.',
            'quantity.*.integer' => 'Số lượng phải là số nguyên.',
            'quantity.*.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
        ];
    }
    /**
     * Customize the data before validation (e.g. handle empty address field)
     */
}

