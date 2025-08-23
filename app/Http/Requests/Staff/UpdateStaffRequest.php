<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,' . $this->id . '',
            'name' => 'required',
            'phone' => 'required|regex:/^[0-9]+$/|min:10|max:11|unique:users,phone,' . $this->id . '',
            'roles' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Không được để trống email',
            'emmail.email' => 'Email phải đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'name.required' => 'Không được để trống tên',
            'phone.required' => 'Không được để trống số điện thoại',
            'phone.regex' => 'Số điện thoại không được nhập chữ',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'phone.min' => 'Số điện thoại phải 10 hoặc 11 số',
            'phone.max' => 'Số điện thoại phải 10 hoặc 11 số',
            'roles.required' => 'Vai trò không được để trống',
        ];
    }
}
