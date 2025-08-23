<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Product\ProductService;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Attribute\AttributeCategoryRepository;
use App\Repositories\Attribute\AttributeRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Review\ReviewRepository;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendTelegramNotification;
use App\Models\Product;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $categoryRepository;
    protected $attributeCategoryRepository;
    protected $attributeRepository;
    protected $productVariantRepository;
    protected $reviewRepository;
    public function __construct(
        ProductService $productService,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        AttributeCategoryRepository $attributeCategoryRepository,
        AttributeRepository $attributeRepository,
        ProductVariantRepository $productVariantRepository,
        ReviewRepository $reviewRepository
    ) {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeCategoryRepository = $attributeCategoryRepository;
        $this->attributeRepository = $attributeRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->reviewRepository = $reviewRepository;
    }
    public function index(Request $request)
    {
        // Lấy các danh mục và thương hiệu để hiển thị sidebar
        $categories = $this->categoryRepository->getParentCategory();
        $brands = $this->categoryRepository->findByWhereIn('is_room', [1], ['products'], ['id', 'name', 'slug']);
        
        // Khởi tạo query
        $query = Product::where('publish', 1);
        
        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('sku', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%')
                  ->orWhere('short_content', 'like', '%' . $keyword . '%');
            });
        }
        
        // Lọc theo thương hiệu
        if ($request->filled('brand_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->brand_id);
            });
        }
        
        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        
        // Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sắp xếp
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Phân trang
        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage)->appends($request->all());
        
        return view('client.pages.product.index', compact('products','categories','brands'));
    }

    public function detail(Request $request, $slug)
    {
        $config = $this->config();
        $product = $this->productRepository->findByWhereIn('slug', [$slug], ['categories', 'productVariants'], )->first();
        $NewAlbums = json_decode($product->albums);
        $product->thumbnail_sub = $NewAlbums[0] ?? $product->thumbnail;
        $historyProduct = Session::get('historyProduct', []);
        if (!collect($historyProduct)->contains('id', $product->id)) {
            $historyProduct[] = $product;
            Session::put('historyProduct', $historyProduct);
        }
        $variant = (object) [];
        if ($product->has_attribute == 1) {
            $product = $this->getAttribute($product);
            $product->productVariants->map(function ($item) {
                $item->attributes = explode(',', $item->attributes);
                return $item;
            });
            $variantCurrent = $product->productVariants->first();

            if ($request->has('attr')) {
                $attr = $request->attr;
                $attr = explode(',', $attr);
                $attr = implode(', ', $attr);
                $variantCurrent = $this->productVariantRepository->findVariant($product->id, $attr);
            }

            $product->sku = $variantCurrent->sku;
            $product->title = $variantCurrent->title;
            $product->price = $variantCurrent->price;
            $product->code = explode(',', $variantCurrent->code);
            $product->quantity = $variantCurrent->quantity;
            $variant->albums = $variantCurrent->albums;
            // dd($variant);
        }
        $product->albums = view('client.pages.product_detail.components.api.albums', compact('variant', 'product'))->render();
        // Session::flush();
        // dd($product);
        $idCategory = $product->categories->where('is_room', 2)->first()->id;
        $productRelated = $this->productRepository->getRelatedProduct($product->id, $idCategory);
        return view('client.pages.product_detail.index', compact(
            'config',
            'product',
            'productRelated'
        ));
    }

    private function getAttribute($product)
    {
        $attributeCategoryId = array_keys($product->attribute);
        $attrCategory = $this->attributeCategoryRepository->findByWhereIn('id', $attributeCategoryId, ['attributes'], ['id', 'name']);
        $attributeId = array_merge(...$product->attribute);
        $attrs = $this->attributeRepository->findByWhereIn('id', $attributeId, [], ['id', 'value', 'attribute_category_id']);
        if (!is_null($attrCategory)) {
            foreach ($attrCategory as $key => $value) {
                $newData = [];
                foreach ($attrs as $attr) {
                    if ($value->id == $attr->attribute_category_id) {
                        $newData[] = $attr;
                    }
                }
                $value->attributes = $newData;
            }
        }
        $product->attribute_category = $attrCategory;
        return $product;
    }

    public function changeQuantity(Request $request)
    {

        $quantity = (int) $request->quantity;
        $inventory = $this->productService->getProductBySku($request->sku)->quantity;
        $data = [];
        if ($quantity <= $inventory) {
            return successResponse(
                $inventory,
                'Cập nhật số lượng'
            );
        } else {
            return $inventory;
        }
    }
    public function getVariant(Request $request)
    {
        $attribute_id = $request->attribute_id;
        sort($attribute_id, SORT_NUMERIC);
        $attribute_id = implode(', ', $attribute_id);
        $variant = $this->productVariantRepository->findVariant($request->product_id, $attribute_id);
        $product = $this->productRepository->findById($request->product_id, ['productVariants'], ['albums', 'name', 'discount']);
        $variant->name = $product->name;
        $variant->discount = $product->discount;
        $variant->albums = view('client.pages.product_detail.components.api.albums', compact('variant', 'product'))->render();
        return successResponse($variant);
    }


    public function getReview(Request $request)
    {
        $rating = $this->reviewRepository->getRatingDetails($request->product_id);
        $reviewForProduct = $this->reviewRepository->getReviews($request->product_id);
        $html = view('client.pages.product_detail.components.api.review', compact('reviewForProduct', 'rating'))->render();
        return successResponse($html);
    }

    public function addReview(Request $request)
    {
        $payload = $request->all();
        $payload['user_id'] = auth()->id();
        $create = $this->reviewRepository->create($payload);
        if (!$create) {
            return errorResponse('Đánh giá sản phẩm thất bại!');
        }
        $product = $this->productRepository->findById($request->product_id);
        $linkReview = route('review.reply', $create->id);
        $linkProduct = route('client.product.detail', $product->slug);
        $message = "🛍️ *Có đánh giá mới cho sản phẩm!*\n\n";
        $message .= "📦 *Thông tin chi tiết:*\n";
        $message .= "📄 *Sản phẩm:* [{$product->name}]($linkProduct)\n";
        $message .= "🔍 *Đánh giá:* {$request->rating} 🌟\n";
        $message .= "👤 *Người đánh giá:* " . auth()->user()->name . "\n";
        $message .= "🔒 *Nội dung:* {$request->content}\n\n";
        $message .= "🔗 *Chi tiết xem đánh giá:* [Xem tại đây]($linkReview)\n";

        SendTelegramNotification::dispatch($message);
        return successResponse(null, 'Đánh giá sản phẩm thành công!');
    }

    public function searchProduct(Request $request)
    {
        $products = $this->productRepository->searchProduct($request->q);
        $categories = $this->categoryRepository->searchCategory($request->q);
        $data = [
            'products' => $products,
            'categories' => $categories
        ];
        return successResponse($data);
    }
    public function addCompare(Request $request, $sku)
    {
        $data = $this->productRepository->findByField('sku', $sku)->first();
        if (empty($data)) {
            $data = $this->productVariantRepository->findByField('sku', $sku)->first();
            $data->name = $data->product->name;
            $data->thumbnail = $data->product->thumbnail;
        }
        return $data;
    }
    public function compare(Request $request)
    {
        $skus = $request->except('_token');
        $products = [];
        foreach ($skus as $sku) {
            $data = $this->productRepository->findByField('sku', $sku)->first();
            if (empty($data)) {
                $data = $this->productVariantRepository->findByField('sku', $sku)->first();
                $data->name = $data->product->name;
                $data->thumbnail = $data->product->thumbnail;
            }
            $products[] = $data;
        }
        return view('client.pages.product.compare', compact('products'))->render();
        ;
    }
    private function config()
    {
        return [
            'css' => [
                'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.css',
                'client_asset/custom/css/product_detail.css',
            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
                'client_asset/custom/js/product/comment_review.js',
                'client_asset/custom/js/product/attribute.js',
                // 'client_asset/custom/js/product/compare.js',
                // 'client_asset/custom/js/product/compare_search.js',
                'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.js',

            ],
        ];
    }

    private function breadcrumb()
    {
        return [
            "detail" => [
                "title" => "Product Detail",
                "url" => route('client.product.detail')
            ]
        ];
    }


}
