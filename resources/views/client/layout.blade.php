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
    <script>
document.addEventListener("DOMContentLoaded", () => {
    const fs = document.querySelector("#flash-sale");
    if (!fs) return;

    const start = new Date(fs.dataset.start);
    const end = new Date(fs.dataset.end);
    const status = document.querySelector("#fs-status");
    const dEl = document.getElementById("d"),
          hEl = document.getElementById("h"),
          mEl = document.getElementById("m"),
          sEl = document.getElementById("s"),
          bar = document.getElementById("bar"),
          startLabel = document.getElementById("startLabel"),
          endLabel = document.getElementById("endLabel");

    startLabel.textContent = start.toLocaleString("vi-VN");
    endLabel.textContent = end.toLocaleString("vi-VN");

    function update() {
        const now = new Date();
        if (now < start) {
            status.textContent = "Sắp diễn ra";
            status.className = "pill soon";
            const diff = start - now;
            showTime(diff);
            bar.style.width = "0%";
        } else if (now >= start && now <= end) {
            status.textContent = "Đang diễn ra";
            status.className = "pill live";
            const diff = end - now;
            showTime(diff);
            const progress = ((now - start) / (end - start)) * 100;
            bar.style.width = progress + "%";
        } else {
            status.textContent = "Đã kết thúc";
            status.className = "pill ended";
            showTime(0);
            bar.style.width = "100%";
        }
    }

    function showTime(ms) {
        let sec = Math.floor(ms / 1000);
        const d = Math.floor(sec / 86400); sec %= 86400;
        const h = Math.floor(sec / 3600); sec %= 3600;
        const m = Math.floor(sec / 60); sec %= 60;
        dEl.textContent = d.toString().padStart(2, "0");
        hEl.textContent = h.toString().padStart(2, "0");
        mEl.textContent = m.toString().padStart(2, "0");
        sEl.textContent = sec.toString().padStart(2, "0");
    }

    update();
    setInterval(update, 1000);
});
</script>

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
    <main class="main">
      @yield('content')
    </main>
    @include('client.components.footer')
    @include('client.components.modal')
    @include('client.components.alert')
    @include('client.components.script')
  </body>

</html>