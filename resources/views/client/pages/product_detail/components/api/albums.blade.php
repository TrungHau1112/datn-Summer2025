@php
    if (!empty($variant->albums) && $variant->albums !== '"0"') {
        $albums = json_decode($variant->albums, true);
        if (!is_array($albums)) {
            $albums = array_map('trim', explode(',', trim($variant->albums, '[]"')));
        }
    } else {
        $albums = json_decode($product->albums, true);
    }

    // Đảm bảo mảng an toàn và không có \/
    if (is_array($albums)) {
        $albums = array_map(function($album) {
            return str_replace('\/', '/', $album);
        }, $albums);
    }

    // dd($albums);
@endphp


<div class="gallery-image">
    <div class="galleries">
        <div class="detail-gallery">
            <div class="product-image-slider">
                @foreach ($albums as $album)
                    <figure class="border-radius-10"><img
                            src="{{ $album }}" alt="product image">
                    </figure>
                @endforeach
            </div>
        </div>
        <div class="slider-nav-thumbnails">
            @foreach ($albums as $album)
                <div>
                    <div class="item-thumb"><img src="{{ $album }}" alt="product image">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
