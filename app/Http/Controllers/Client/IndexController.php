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
        $brands = $this->categoryRepository->getCategoryRoom()->where('deleted_at', null);
        $categories = $this->categoryRepository->getParentCategory()->where('deleted_at', null);
        $slides = $this->slideRepository->getAll();
        $posts = Post::where('publish', 1)->where('deleted_at', null)->orderBy('created_at', 'desc')->take(10)->get();
        $product_bestsellers = $this->productRepository->getBestsellers()->where('deleted_at', null)->take(4);
        $config = $this->config();
        $product_featureds = $this->productRepository->getFeatured()->where('deleted_at', null);
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
        return redirect()->route('home');
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
