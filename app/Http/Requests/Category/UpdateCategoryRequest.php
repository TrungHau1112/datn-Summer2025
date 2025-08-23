<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:categories,name, '. $this->id, 
            'publish' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg' 
            // 'meta_title' => 'nullable|string|max:255',
            // 'meta_description' => 'nullable|string|max:500',
            // 'meta_keyword' => 'nullable|string|max:255',
        ];
    }
      public function massages(): array {
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'name' => 'Danh mục đã tồn tại',
            'thumbnail.required' => 'Ảnh đại diện chưa được chọn'
        ];
    }
}
