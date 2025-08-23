@component('mail::message')
    # Yêu cầu đặt lại mật khẩu

    Xin chào, {{ $user->name }}!

    Chúng tôi nhận được yêu cầu đặt lại mật khẩu từ tài khoản của bạn.
    Bấm vào nút bên dưới để đặt lại mật khẩu:

    @component('mail::button', ['url' => $resetUrl])
        Đặt lại mật khẩu
    @endcomponent

    Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.

    Cảm ơn,<br>
    {{ config('app.name') }}
@endcomponent
