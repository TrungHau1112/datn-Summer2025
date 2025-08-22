<?php

namespace App\Http\Requests\order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'province_id' => 'required|exists:provinces,code',
            'district_id' => 'required|exists:districts,code',
            'ward_id' => 'required|exists:wards,code',
            'note' => 'nullable|string|max:500',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:completed,pending,failed,refunded',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'address' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'cart' => 'required|array|min:1',
            'sku' => 'required|array|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên khách hàng là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'province_id.required' => 'Tỉnh/thành phố là bắt buộc.',
            'province_id.exists' => 'Tỉnh/thành phố không hợp lệ.',
            'district_id.required' => 'Quận/huyện là bắt buộc.',
            'district_id.exists' => 'Quận/huyện không hợp lệ.',
            'ward_id.required' => 'Phường/xã là bắt buộc.',
            'ward_id.exists' => 'Phường/xã không hợp lệ.',
            'payment_method_id.required' => 'Phương thức thanh toán là bắt buộc.',
            'payment_method_id.exists' => 'Phương thức thanh toán không tồn tại.',
            'status.required' => 'Trạng thái đơn hàng là bắt buộc.',
            'payment_status.required' => 'Trạng thái thanh toán là bắt buộc.',
            'address.required' => 'Địa chỉ giao hàng là bắt buộc.',
            'total.required' => 'Tổng tiền là bắt buộc.',
            'total.numeric' => 'Tổng tiền phải là số.',
            'cart.required' => 'Giỏ hàng không được để trống.',
            'cart.array' => 'Giỏ hàng không hợp lệ.',
            'sku.required' => 'Bạn chưa có sản phẩm nào.',
            'sku.array' => 'Dữ liệu sản phẩm không hợp lệ.',
        ];
    }
}
