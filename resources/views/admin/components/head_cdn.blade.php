<title>ADMIN</title>
<!-- [Meta] -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- [Favicon] icon -->
<link rel="shortcut icon" href="{{ asset('logo_tgnt.ico') }}" type="image/x-icon">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/fonts/inter/inter.css"
    id="main-font-link" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/fonts/phosphor/duotone/style.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/fonts/tabler-icons.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/fonts/feather.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/fonts/fontawesome.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/fontawesome-pro/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/fonts/material.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/css/plugins/animate.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/css/style.css"
    id="main-style-link" />

<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/tech-stack.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/css/style-preset.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/css/customize.css">

<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/jquery-3.1.1.min.js"></script>

<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/sweetalert2/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/sweetalert2/sweetalert2.min.js"></script>

<link href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/fancybox/fancybox.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/fancybox/fancybox.umd.js"></script>

<link href="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/message/message.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">

<style>
    .select2-results__option[aria-disabled="true"] {
        background-color: #e9ecef;
    }
    .hidden {
        visibility: hidden !important;
    }
</style>

@if (isset($config['css']) && count($config['css']))
    @foreach ($config['css'] as $key => $val)
        <link href="{{ asset($val) }}" rel="stylesheet" />
    @endforeach
@endif

<script>
    $(document).ready(function() {
        Fancybox.bind('[data-fancybox="gallery"]', {
            infinite: false,
            buttons: [
                "zoom",
                "close",
            ],
        });
        const VDmessage = new VdMessage();
    })
</script>
