<?php

namespace App\Http\Requests\ClientAuth;

use Illuminate\Foundation\Http\FormRequest;

class ChangeForgetPassClientRequest extends FormRequest
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
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải lớn hơn 6 kí tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ];
    }
}
