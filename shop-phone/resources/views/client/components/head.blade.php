<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/client_asset/custom/css/color.css') }}" />
<link rel="stylesheet" href="/client_asset/custom/css/footer.css" />
<link rel="stylesheet" href="/client_asset/custom/css/header.css" />
<link rel="stylesheet" href="/client_asset/custom/css/home.css" />
<link rel="stylesheet" href="/client_asset/custom/css/custom.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/fontawesome-pro/all.min.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/client_asset/library/bootstrap5.3/dist/css/theme.min.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/client_asset/library/icon/feather-webfont/dist/feather-icons.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/client_asset/library/animate/animate.min.css" />
<!-- slick slide -->
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/jquery-3.1.1.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/client_asset/library/bootstrap5.3/dist/css/owl.carousel.min.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/select2/css/select2.min.css">

<link href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/fancybox/fancybox.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/fancybox/fancybox.umd.js"></script>

<link href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Lemon&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Salsa&display=swap"
    rel="stylesheet">@if (isset($config['css']) && count($config['css']))
        @foreach ($config['css'] as $key => $val)
            <link href="{{ asset($val) }}" rel="stylesheet" />
        @endforeach
    @endif

<script>
    $(document).ready(function () {
        Fancybox.bind('[data-fancybox="gallery"]', {
            infinite: false,
            buttons: [
                "zoom",
                "close",
            ],
        });
    })
</script>