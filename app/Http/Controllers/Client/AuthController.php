<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\VerifyAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepository;
use App\Http\Requests\ClientAuth\LoginClientRequest;
use App\Http\Requests\ClientAuth\RegisterClientRequest;
use App\Http\Requests\ClientAuth\ForgetPassClientRequest;
use App\Http\Requests\ClientAuth\ChangeForgetPassClientRequest;
use App\Jobs\SendActiveMail;
use Throwable;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login()
    {
        return view('client.auth.login');
    }

    public function register()
    {
        return view('client.auth.register');
    }
    public function postRegister(RegisterClientRequest $request)
    {
        $data = $request->only('name', 'email');
        $data['password'] = bcrypt($request->password);
        if ($account = User::create($data)) {
            SendActiveMail::dispatch($account);
            return redirect()->route('client.auth.login')->with('success', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản');
        }
        return redirect()->back()->with('error', 'Đăng ký không thành công');
    }
    public function postLogin(LoginClientRequest $request)
    {
        // Validate reCAPTCHA v2
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if ($user && is_null($user->email_verified_at)) {
            return back()->withErrors(['email' => 'Tài khoản của bạn chưa được kích hoạt.'])->onlyInput('email');
        }
        if ($user->publish !== 1) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị vô hiệu hoá.'])->onlyInput('email');
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Đăng nhập thành công');
        }
        return back()->withErrors([
            'password' => 'Mật khẩu không chính xác.',
        ])->onlyInput('email');
    }


    public function forget()
    {
        return view('client.auth.forget_password.forget');
    }


    public function submitForgetPasswordForm(ForgetPassClientRequest $request)
    {
        $token = strtoupper(Str::random(10));
        $payload = [
            'remember_token' => $token
        ];
        // dd($payload);
        $user = $this->userRepository->findByField('email', $request->email)->first();
        $update = $this->userRepository->updateByWhereIn('email', [$request->email], $payload);
        Mail::send('client.emails.reset-password-client', ['user' => $user, 'token' => $token], function ($email) use ($user) {
            $email->subject('Đặt lại mật khẩu');
            $email->to($user->email, $user->name);
        });
        return redirect()->back()->with('success', 'Kiểm tra email để đặt lại mật khẩu');
    }


    public function change(User $user, $token)
    {
        if ($user->remember_token === $token) {
            return view('client.auth.forget_password.change', ['user' => $user->id, 'token' => $token]);
        }
        return redirect()->route('client.auth.login')->withErrors(['token' => 'Token không hợp lệ.']);
    }


    public function submitChangePasswordForm(User $user, $token, ChangeForgetPassClientRequest $request)
    {
        // dd($user, $token, $request->all());

        if ($user && $user->remember_token === $token) {
            $update = $user->update([
                'password' => Hash::make($request->password),
                'remember_token' => null,
            ]);

            return redirect()->route('client.auth.login')->with('success', 'Đặt lại mật khẩu thành công');
        }

        return back()->withErrors(['token' => 'Token không hợp lệ hoặc đã hết hạn.']);
    }


    public function active($email)
    {
        $account = User::where('email', $email)->whereNUll('email_verified_at')->firstOrFail();
        User::where('email', $email)->update([
            'email_verified_at' => now(),
            'publish' => 1
        ]);
        return redirect()->route('client.auth.login')->with('success', 'Kích hoạt thành công');
    }

    public function redirect()
    {
        return Socialite::driver("google")->redirect();
    }

    public function callback()
    {
        try {
            $socialUser = Socialite::driver("google")->user();
            if (!$socialUser->getEmail()) {
                return redirect()->route('home')->with('error', 'Tài khoản không có email hợp lệ!');
            }

            $user = User::updateOrCreate(
                [
                    'email' => $socialUser->getEmail(),
                ],
                [
                    'avatar' => $socialUser->getAvatar(),
                    'name' => $socialUser->getName(),
                    'password' => Hash::make(Str::random(16)),
                    'provider_id' => $socialUser->getId(),
                    'email_verified_at' => now(),
                    'publish' => 1
                ]
            );
            Auth::login($user);
            return redirect('/')->with('success', 'Đăng nhập thành công!');
        } catch (Throwable $e) {
            return redirect()->route('client.auth.login')->with('error', 'Có lỗi xảy ra khi đăng nhập!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('client.auth.login')->with('success', 'Đăng xuất thành công');
    }
}

