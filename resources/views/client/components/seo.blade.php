{{-- SEO product, category --}}
@if (isset($product))
    <title>{{ $product->name }}</title>
    <meta name="description" content="{{ $product->meta_description }}">
    <meta property="og:title" content="{{ $product->meta_title }}">
    <meta property="og:description" content="{{ $product->meta_description }}">
    <meta property="og:image" content="{{ asset($product->thumbnail) }}">
    <meta property="og:url" content="{{ route('client.product.detail', $product->slug) }}">
    <meta property="og:type" content="product">
@elseif(isset($category))
    <title>Danh mục - {{ $category->name }}</title>
    <meta name="description" content="{{ $category->meta_description }}">
    <meta property="og:title" content="{{ $category->meta_title }}">
    <meta property="og:description" content="{{ $category->meta_description }}">
    <meta property="og:image" content="{{ $category->thumbnail }}">
    <meta property="og:url" content="{{ route('client.category.index', $category->slug) }}">
    <meta property="og:type" content="website">
@endif

{{-- SEO home --}}
@if (isset($home))
    <title>{{ getSetting()->site_name }}</title>
    <meta name="description" content="{{ $home->meta_description }}">
    <meta property="og:title" content="{{ $home->meta_title }}">
    <meta property="og:description" content="{{ $home->meta_description }}">
    <meta property="og:image" content="{{ $home->image }}">
    <meta property="og:url" content="{{ route('client.home') }}">
    <meta property="og:type" content="website">
@endif

{{-- SEO collection --}}
@if (isset($collection))
    <title>Bộ sưu tập - {{ $collection->name }}</title>
    <meta name="description" content="{{ $collection->meta_description }}">
    <meta property="og:title" content="{{ $collection->meta_title }}">
    <meta property="og:description" content="{{ $collection->meta_description }}">
    <meta property="og:image" content="{{ $collection->thumbnail }}">
    <meta property="og:url" content="{{ route('client.collection.detail', $collection->slug) }}">
    <meta property="og:type" content="article">
@endif