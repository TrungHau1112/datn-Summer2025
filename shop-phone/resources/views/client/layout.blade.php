<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="msapplication-TileColor" content="#0E0E0E">
    <meta name="template-color" content="#0E0E0E">
    @yield('seo')
    <title>{{ $title ?? getSetting()->site_name  }}</title>
    <link rel="shortcut icon" href="{{ asset('logo_fashion.ico') }}" type="image/x-icon">
    <link href="{{ asset( 'client_asset_v1/css/style.css') }}" rel="stylesheet">
    <script src="/client_asset_v1/js/vendors/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/client_asset/custom/css/color.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/select2/css/select2.min.css">
    <script>
      window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    </head>
  <body>
    <div id="preloader-active">
      <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
          <div class="text-center"><img class="mb-10" src="/client_asset_v1/imgs/template/favicon.svg" alt="Ecom">
            <div class="preloader-dots"></div>
          </div>
        </div>
      </div>
    </div>
    @include('client.components.header')
    <main class="main mb-5">
      @yield('content')
    </main>
    @include('client.components.footer')
    @include('client.components.modal')
    @include('client.components.alert')
    @include('client.components.script')
  </body>

</html>