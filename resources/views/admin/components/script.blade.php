<script src="{{ asset('/admin_asset/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/admin_asset/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/LieutenantPeacock/SmoothScroll@1.2.0/src/smoothscroll.min.js"
    integrity="sha384-UdJHYJK9eDBy7vML0TvJGlCpvrJhCuOPGTc7tHbA+jHEgCgjWpPbmMvmd/2bzdXU" crossorigin="anonymous">
</script>
<script src="{{ asset('admin_asset/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('admin_asset/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('admin_asset/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin_asset/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('admin_asset/js/pcoded.js') }}"></script>
<script src="{{ asset('admin_asset/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('admin_asset\plugins\choices\choices.min.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('admin_asset/library/finder.js') }}"></script>
<script src="{{ asset('admin_asset/library/seo.js') }}"></script>
<script src="/admin_asset/library/dataTables.js"></script>
<script src="{{ asset('admin_asset/library/library.js') }}"></script>


@if (isset($config['js']) && count($config['js']))
    @foreach ($config['js'] as $key => $val)
        <script src="{{ asset($val) }}"></script>
    @endforeach
@endif

<script>
    const BASE_URL = '{{ url('/') }}';
</script>
{{-- <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script> --}}

<script>
    layout_change("light");
</script>
<script>
    change_box_container("false");
</script>
<script>
    layout_caption_change("true");
</script>
<script>
    layout_rtl_change("false");
</script>
<script>
    preset_change("preset-9");
</script>
<script>
    main_layout_change("vertical");
</script>

<script>
    $(document).ready(function() {
        if ($('.js-choice-multiple').length || $('.js-choice').length) {
            new Choices('.js-choice-multiple', {
                removeItemButton: true,
                allowHTML: true,
            });
            new Choices('.js-choice', {
                allowHTML: true,
            });
        }

    });
</script>
