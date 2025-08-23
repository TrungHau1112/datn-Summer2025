<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function __construct()
    {
    }


    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $user = User::where('email', $request->input('email'))->first();
        if (isset($user) && $user->publish !== 1) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị vô hiệu hoá.'])->onlyInput('email');
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác',
        ])->onlyInput('email');
    }

    public function forget()
    {
        return view('admin.auth.forget');
    }

    public function postForgetPass(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không tồn tại trong hệ thống'

        ]);

        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        } else {
            return back()->with('error', __($status));
        }
    }

    // Hiển thị trang thay đổi mật khẩu
    public function resetPassword(Request $request, $token, $email)
    {
        return view('admin.auth.change', ['token' => $token, 'email' => $email]);
    }

    public function postChangePass(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'token.required' => 'Token là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('auth.index')->with('success', __($status))
            : back()->with('error', __($status));

    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.index')->with('success', 'Đăng xuất thành công');
    }

}
