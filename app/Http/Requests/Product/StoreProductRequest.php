<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|unique:products',
            // sku chỉ lấy số và chữ không lấy ký tự đặc biệt và dẫu chấm
            'sku' => 'required|regex:/^[a-zA-Z0-9]*$/|unique:products|min:6',
            'price' => 'required',
            'quantity' => 'required',
            'category' => 'required|not_in:0',
            'category_id' => 'required|array|min:1|not_in:0',
            'category_id.*' => 'required',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'sku.required' => 'Mã sản phẩm không được để trống',
            'sku.regex' => 'Mã sản phẩm không được chứa khoảng trắng hoặc ký tự đặc biệt',
            'sku.unique' => 'Mã sản phẩm đã tồn tại',
            'sku.min' => 'Mã sản phẩm phải có ít nhất 6 ký tự',
            'price.required' => 'Giá sản phẩm không được để trống',
            'quantity.required' => 'Số lượng sản phẩm không được để trống',
            'category.required' => 'Danh mục không được để trống',
            'category.not_in' => 'Danh mục không hợp lệ',
            'category_id.required' => 'Danh mục không được để trống',
            'category_id.min' => 'Danh mục không hợp lệ',
            'category_id.*.required' => 'Danh mục không được để trống',
        ];
    }
}
