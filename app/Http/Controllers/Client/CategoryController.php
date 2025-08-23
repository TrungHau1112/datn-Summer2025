<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Category\CategoryRepository;
use App\Services\Product\ProductService;

class CategoryController extends Controller
{
    protected $categoryRepository;
    protected $productService;
    public function __construct(
        CategoryRepository $categoryRepository,
        ProductService $productService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productService = $productService;
    }
    public function index($slug)
    {
        $category = $this->categoryRepository->findByField('slug', $slug, )->first();
        $request = [
            'category_id' => $category->id,
        ];
        $products = $this->productService->paginateClient($request);
        // dd($products);
        $config = $this->config();
        return view('client.pages.category.index', compact(
            'category',
            'config',
            'products'
        ));
    }

    public function getProduct(Request $request)
    {
        $products = $this->productService->paginateClient($request);
        return successResponse(view('client.pages.category.components.product', compact('products'))->render());
    }

    private function config()
    {
        return [
            'css' => [
                // 'client/pages/category/category'
            ],
            'js' => [
                'client_asset/custom/js/product/getData.js'
            ],
            'model' => 'category'
        ];
    }
}
