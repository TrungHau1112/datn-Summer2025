<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:posts,slug',
            'excerpt'     => 'nullable|string',
            'content'     => 'required|string',
            'thumbnail'   => 'nullable|max:2048', // max 2MB
            'published_at' => 'nullable|date',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'       => 'Tiêu đề không được để trống.',
            'title.max'            => 'Tiêu đề không được vượt quá 255 ký tự.',
            'slug.max'             => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique'          => 'Slug đã tồn tại. Vui lòng chọn slug khác.',
            'content.required'     => 'Nội dung không được để trống.',
            'thumbnail.image'      => 'Ảnh đại diện phải là định dạng hình ảnh.',
            'thumbnail.max'        => 'Ảnh đại diện không được vượt quá 2MB.',
            'published_at.date'    => 'Ngày xuất bản không hợp lệ.',
        ];
    }
}
