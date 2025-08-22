<?php

namespace App\Http\Requests\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'password' => 'required|current_password',
            'password_new' => 'required|min:6',
            're_password_new' => 'required|same:password_new',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Mật khẩu hiện tại không được để trống.',
            'password.current_password' => 'Mật khẩu hiện tại không đúng.',
            'password_new.required' => 'Mật khẩu mới không được để trống.',
            // 'password_new.current_password' => 'Mật khẩu mới không được giống với mật khẩu cũ.',
            'password_new.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            're_password_new.required' => 'Vui lòng nhập lại mật khẩu mới.',
            're_password_new.same' => 'Mật khẩu nhập lại không khớp với mật khẩu mới.',
        ];
    }

}
