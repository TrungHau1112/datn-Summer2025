<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductService;
use App\Repositories\Attribute\AttributeCategoryRepository;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;


class ProductController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Product');
    }
    protected $productRepository;
    protected $productService;
    protected $attributeCategoryRepository;
    protected $categoryRepository;
    function __construct(
        ProductRepository $productRepository,
        ProductService $productService,
        AttributeCategoryRepository $attributeCategoryRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->attributeCategoryRepository = $attributeCategoryRepository;
        $this->categoryRepository = $categoryRepository;
    }
    public function index(Request $request)
    {
        // dd($this->productRepository->findByField('id',93)->first());
        $products = $this->productService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.product.product.index', compact(
            'config',
            'products'
        ));
    }
    public function getData($request)
    {
        $products = $this->productService->paginate($request);
        $config = $this->config();
        return view('admin.pages.product.product.components.table', compact('products', 'config'));
    }
    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        // random sku
        $sku = 'SP' . time();
        $attributes = $this->attributeCategoryRepository->getAll();
        $categories = $this->categoryRepository->getAllPublish();
        return view('admin.pages.product.product.save', compact(
            'config',
            'attributes',
            'categories',
            'sku'
        ));
    }

    public function store(StoreProductRequest $request)
    {
        if ($this->productService->create($request)) {
            return redirect()->route('product.index')->with('success', 'Tạo sản phẩm thành công');
        } else {
            return redirect()->back()->with('error', 'Tạo sản phẩm thất bại');
        }
    }

    public function edit($id)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        $product = $this->productRepository->findByid($id, ['categories', 'productVariants', 'productVariants.productVariantAttributes']);
        $attributes = $this->attributeCategoryRepository->getAll();
        $categories = $this->categoryRepository->getAllPublish();
        return view('admin.pages.product.product.save', compact(
            'config',
            'product',
            'attributes',
            'categories'
        ));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        if ($this->productService->update($request, $id)) {
            return redirect()->route('product.index')->with('success', 'Cập nhật sản phẩm thành công');
        } else {
            return redirect()->back()->with('error', 'Cập nhật sản phẩm thất bại');
        }
    }


    public function categories()
    {
        return [
            [
                'id' => 1,
                'name' => 'Giường',
                'parent_id' => 0,
                'is_room' => 2
            ],
            [
                'id' => 2,
                'name' => 'Phòng khách',
                'parent_id' => 0,
                'is_room' => 1
            ],
            [
                'id' => 3,
                'name' => 'Phòng Ngủ',
                'parent_id' => 0,
                'is_room' => 1
            ],
            [
                'id' => 4,
                'name' => 'Ghế',
                'parent_id' => 0,
                'is_room' => 2
            ],
            [
                'id' => 5,
                'name' => 'Tủ',
                'parent_id' => 0,
                'is_room' => 2
            ]
        ];
    }

    public function trash()
    {
        $products = $this->productRepository->getOnlyTrashed();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.product.product.trash', compact(
            'config',
            'products'
        ));
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Danh sách sản phẩm',
                'list' => ['Danh sách sản phẩm']
            ],
            'create' => [
                'name' => 'Tạo sản phẩm',
                'list' => ['QL sản phẩm', 'Tạo sản phẩm']
            ],
            'update' => [
                'name' => 'Cập nhật sản phẩm',
                'list' => ['QL sản phẩm', 'Cập nhật sản phẩm']
            ],
            'delete' => [
                'name' => 'Xóa sản phẩm',
                'list' => ['QL sản phẩm', 'Xóa sản phẩm']
            ],
            'trash' => [
                'name' => 'Thùng rác',
                'list' => ['QL sản phẩm', 'Thùng rác']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [
                'admin_asset\plugins\nice-select\css\nice-select.css',
                'https://unpkg.com/slim-select@latest/dist/slimselect.css'
            ],
            'js' => [

                'admin_asset/library/variant.js',
                'admin_asset\plugins\nice-select\js\jquery.nice-select.min.js',
                'https://unpkg.com/slim-select@latest/dist/slimselect.min.js'
            ],
            'model' => 'product'
        ];
    }


}
