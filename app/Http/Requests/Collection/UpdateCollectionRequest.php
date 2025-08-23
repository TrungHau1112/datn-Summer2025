<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
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
        if (is_string($this->skus)) {
            $this->merge([
                'skus' => array_map('trim', explode(',', $this->skus))
            ]);
        }
        return [
            'name' => 'required|unique:collections,name,'.$this->id.'',
            'short_description' => 'required',
            'description_text' => 'required',
            'skus' => 'min:2',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'Không được để trống tên bộ sưu tập',
            'name.unique' => 'Tên bộ sưu tập đã tồn tại',
            'short_description.required' => 'Mô tả ngắn không được để trống',
            'description_text.required' => 'Nội dung không được để trống',
            'idProduct.min' => 'Hãy chọn ít nhất 3 sản phẩm cho bộ sửu tập',
        ];
    }
}
