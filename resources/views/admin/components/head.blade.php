<title>ADMIN</title>
<!-- [Meta] -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- [Favicon] icon -->
<link rel="icon" href="https://ableproadmin.com/assets/images/favicon.svg" type="image/x-icon" />
<!-- [Font] Family -->
<link rel="stylesheet" href="{{ asset('admin_asset/fonts/inter/inter.css') }}" id="main-font-link" />
<link rel="stylesheet" href="{{ asset('admin_asset/fonts/phosphor/duotone/style.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/fonts/tabler-icons.min.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/fonts/feather.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/fonts/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/plugins/fontawesome-pro/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_asset/fonts/material.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/css/plugins/animate.min.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/css/style.css') }}" id="main-style-link" />
<script src="{{ asset('admin_asset/js/tech-stack.js') }}"></script>
<link rel="stylesheet" href="{{ asset('admin_asset/css/style-preset.css') }}" />
<link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_asset\plugins\select2\css\select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_asset/css/customize.css') }}">

<script src="{{ asset('admin_asset/js/jquery-3.1.1.min.js') }}"></script>

<link rel="stylesheet" href="{{ asset('admin_asset/plugins/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('admin_asset/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('admin_asset/library/cusSweetAlert.js') }}"></script>

<link href="{{ asset('admin_asset/plugins/fancybox/fancybox.css') }}" rel="stylesheet">
<script src="{{ asset('admin_asset/plugins/fancybox/fancybox.umd.js') }}"></script>

<link href="{{ asset('admin_asset/plugins/message/message.css') }}" rel="stylesheet">
<script src="{{ asset('admin_asset/plugins/message/message.js') }}"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">


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
