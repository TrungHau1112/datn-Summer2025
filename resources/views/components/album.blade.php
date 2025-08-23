@props(['label', 'name', 'value'])

<div class="form-group">
    <label for="{{ $name }}" class="control-label text-left">{{ $label }}</label>
    <div class="card">
        <div class="card-body pb-2">
            @php
                $gallery = old($name, $value ?? '');
            @endphp
            <div class="row">
                <div class="col-lg-12">
                    <div class="upload-list mt-2">
                        <ul id="sortable"
                            class="row align-items-center list-unstyled clearfix data-album sortui ui-sortable"
                            style="margin-bottom:0 !important">
                            <li
                                class="col-xl-2 col-md-3 col-sm-6 mb-3 d-flex justify-content-center align-items-center">
                                <a href="#" style="font-size: 50px" class="upload-picture"
                                    data-name="{{ $name }}">
                                    <i class="fa-duotone fa-solid fa-cloud-arrow-up"></i>
                                </a>
                            </li>

                            @if (isset($gallery) && $gallery != '')
                                @foreach ($gallery as $key => $val)
                                    <li class="ui-state-default img_li_tgnt col-xl-2 col-md-3 col-sm-6 mb-3">
                                        <div class="thumb img_albums_tgnt">
                                            <span class="span image img-scaledown">
                                                <a href="{{ $val }}" data-fancybox="gallery" data-caption="">
                                                    <img width="100%" class="img-thumbnail" src="{{ $val }}"
                                                        alt="">
                                                </a>
                                                <input type="hidden" name="{{ $name }}[]"
                                                    value="{{ $val }}">
                                            </span>
                                            <div class="text-center btn_delete_albums_tgnt">
                                                <button type="button" class="delete-image btn btn-sm btn-light-danger"
                                                    title="Delete Image">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
