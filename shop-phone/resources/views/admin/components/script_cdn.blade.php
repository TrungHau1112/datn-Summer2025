
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/select2/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/LieutenantPeacock/SmoothScroll@1.2.0/src/smoothscroll.min.js" integrity="sha384-UdJHYJK9eDBy7vML0TvJGlCpvrJhCuOPGTc7tHbA+jHEgCgjWpPbmMvmd/2bzdXU" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/plugins/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/plugins/simplebar.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/plugins/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/fonts/custom-font.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/pcoded.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/js/plugins/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/vudevweb/my-library/able_pro/plugins/choices/choices.min.js"></script>

<script src="{{ asset('admin_asset/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('admin_asset/library/finder.js')}}"></script>
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
        if($('.js-choice-multiple').length || $('.js-choice').length){
            new Choices('.js-choice-multiple', {
                removeItemButton: true,
                allowHTML: true,
            });
            new Choices('.js-choice',{
            allowHTML: true,
        });
        }
       
   });
</script>
<script>
    $(document).ready(function() {
        $("#discount").on("input", function() {
            var value = $(this).val();
            var numericValue = value.replace(/[^0-9.]/g, "");
            if ((numericValue.match(/\./g) || []).length > 1) {
                numericValue = numericValue.substring(0, numericValue.lastIndexOf("."));
            }
            if (numericValue.includes(".")) {
                var parts = numericValue.split(".");
                numericValue = parts[0] + "." + parts[1].substring(0, 2);
            }
            var floatValue = parseFloat(numericValue);
            if (isNaN(floatValue)) {
                floatValue = "";
            } else if (floatValue > 50) {
                floatValue = 50;
            } else if (floatValue < 0) {
                floatValue = 0;
            }
            $(this).val(floatValue);
        });
    });
</script>