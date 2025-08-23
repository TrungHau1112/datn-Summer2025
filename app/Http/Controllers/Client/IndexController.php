<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Slide\SlideRepository;
use App\Repositories\Product\ProductRepository;
use App\Models\Product;
use App\Models\Post;
class IndexController extends Controller
{
    protected $categoryRepository;
    protected $slideRepository;
    protected $productRepository;
    public function __construct(
        CategoryRepository $categoryRepository,
        SlideRepository $slideRepository,
        ProductRepository $productRepository,
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->slideRepository = $slideRepository;
        $this->productRepository = $productRepository;
    }

    public function home()
    {
        $brands = $this->categoryRepository->getCategoryRoom();
        $categories = $this->categoryRepository->getParentCategory();
        $slides = $this->slideRepository->getAll();
        $posts = Post::where('publish', 1)->orderBy('created_at', 'desc')->take(10)->get();
        $product_bestsellers = $this->productRepository->getBestsellers()->take(4);
        $config = $this->config();
        $product_featureds = $this->productRepository->getFeatured();
        // tách sản phẩm nổi bật thành 2 mảng 4 sản phẩm khác nhau
        $product_featureds_1 = $product_featureds->take(6);
        $product_featureds_2 = $product_featureds->slice(6, 6);
        return view('client.pages.home.index', compact(
            'brands',
            'categories',
            'config',
            'slides',
            'posts',
            'product_featureds_1',
            'product_featureds_2',
            'product_bestsellers'
        ));
    }

    public function about()
    {
        $config = $this->config();
        return view('client.pages.about.index', compact('config'));
    }

    public function search(Request $request)
    {
        $config = $this->config();
        
        // Lấy các danh mục và thương hiệu để hiển thị sidebar
        $brands = $this->categoryRepository->getCategoryRoom();
        $categories = $this->categoryRepository->getParentCategory();
        
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
        
        return view('client.pages.search.index', compact(
            'config', 
            'products', 
            'brands', 
            'categories'
        ));
    }


    private function config()
    {
        return [
            'css' => [
                "client_asset/custom/css/about.css",
                "client_asset/custom/css/contact.css",
                "client_asset/custom/css/color.css",
            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
                "client_asset/custom/js/home.js",
            ],
            'model' => ''
        ];
    }
}
