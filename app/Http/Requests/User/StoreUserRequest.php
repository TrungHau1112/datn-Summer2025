<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'phone' => 'required|regex:/^[0-9]+$/|min:10|max:11|unique:users,phone',
            'password' => 'required|min:6',
            're_password' => 'required|same:password',
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
            'password.required' => 'Không được để trống mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',
            're_password.required' => 'Không được để trống mật khẩu xác nhận',
            're_password.same' => 'Mật khẩu xác nhận không khớp với mật khẩu đã nhập',
        ];
    }
}
