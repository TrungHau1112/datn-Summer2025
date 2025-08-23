<?php

namespace App\Http\Requests\ClientAuth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterClientRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password-confirm' => 'required|same:password',
        ];
    }
    public function messages() {
        return [
            'name.required' => 'Vui lòng nhập họ & tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải lớn hơn 6 kí tự',
            'password-confirm.required' => 'Vui lòng nhập mật khẩu xác nhận',
            'password-confirm.same' => 'Mật khẩu xác nhận không trùng khớp',
        ];
    }
}
