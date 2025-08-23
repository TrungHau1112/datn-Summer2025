<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Collection\CollectionProductRepository;
use App\Repositories\Collection\CollectionRepository;
use App\Services\Collection\CollectionService;
use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Requests\Collection\UpdateCollectionRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Collection');
    }
    protected $collectionRepository;
    protected $collectionService;
    protected $productRepository;
    protected $productVariantRepository;
    protected $collectionProductRepository;
    function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        CollectionRepository $collectionRepository,
        CollectionService $collectionService,
        collectionProductRepository $collectionProductRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->collectionRepository = $collectionRepository;
        $this->collectionProductRepository = $collectionProductRepository;
        $this->collectionService = $collectionService;
    }
    public function index(Request $request)
    {
        $collections = $this->collectionService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.collection.index', compact(
            'config',
            'collections'
        ));
    }

    public function getData($request)
    {
        $collections = $this->collectionService->paginate($request);
        $config = $this->config();
        return view('admin.pages.collection.components.table', compact('collections', 'config'));
    }
    public function getProductPoint(Request $request)
    {
        $data = $this->productRepository->findByField('sku', $request->sku)->first();
        if ($data) {
            $category = $data->categories->where('is_room', 2)->first();
            $data->category = $category ? strtolower($category->name) : '';
        }
        if (empty($data->sku)) {
            $data = $this->productVariantRepository->findByField('sku', $request->sku)->first();
            if ($data && $data->product) {
                $data->name = $data->product->name ?? '';
                $data->slug = $data->product->slug ?? '';
                $data->thumbnail = $data->product->thumbnail;
                $category = $data->product->categories->where('is_room', 2)->first();
                $data->category = $category ? strtolower($category->name) : '';
            }
        }
        return $data;
    }
    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        $categories = Category::where('is_room', 2)->pluck('name', 'id')->prepend('Danh mục', 0)->toArray();
        $categoryRoom = Category::where('is_room', 1)->pluck('name', 'id')->prepend('Phòng', 0)->toArray();
        return view('admin.pages.collection.save', compact(
            'config',
            'categoryRoom',
            'categories',
        ));
    }
    public function store(StoreCollectionRequest $request)
    {
        $collection = $this->collectionService->create($request);
        if ($collection) {
            return redirect()->route('collection.index')->with('success', 'Tạo bộ sưu tập mới thành công');
        }
        return redirect()->back()->with('error', 'Tạo bộ sưu tập mới thất bại');
    }
    public function edit($id)
    {
        $collection = $this->collectionRepository->findById($id);
        $categories = $this->getCategories(2, 'Danh mục');
        $categoryRoom = $this->getCategories(1, 'Phòng');
        $skus = $this->getSkus($id);
        $config = array_merge($this->config(), [
            'breadcrumb' => $this->breadcrumb('update'),
            'method' => 'edit',
        ]);
        return view('admin.pages.collection.save', compact(
            'config',
            'collection',
            'categories',
            'categoryRoom',
            'skus'
        ));
    }

    public function update(UpdateCollectionRequest $request, $id)
    {
        $collection = $this->collectionService->update($request, $id);
        if ($collection) {
            return redirect()->route('collection.index', ['page' => $request->page])->with('success', 'Cập nhật người dùng thành công.');
        }
        return redirect()->route('collection.index')->with('error', 'Cập nhật người dùng thất bại');
    }

    public function trash()
    {
        $collections = $this->collectionRepository->getOnlyTrashed();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.collection.trash', compact(
            'config',
            'collections'
        ));
    }

    private function getCategories($isRoom, $defaultLabel)
    {
        return Category::where('is_room', $isRoom)
            ->pluck('name', 'id')
            ->prepend($defaultLabel, 0)
            ->toArray();
    }

    private function getSkus($collectionId)
    {
        $skuProduct = $this->collectionProductRepository
            ->findByField('collection_id', $collectionId)
            ->pluck('product_sku')
            ->toArray();

        $skuVariant = $this->collectionProductRepository
            ->findByField('collection_id', $collectionId)
            ->pluck('productVariant_sku')
            ->toArray();

        return implode(',', array_filter(array_merge($skuProduct, $skuVariant)));
    }
    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Danh sách bộ sưu tập',
                'list' => ['Danh sách bộ sưu tập']
            ],
            'create' => [
                'name' => 'Tạo bộ sưu tập',
                'list' => ['QL bộ sưu tập', 'Tạo bộ sưu tập']
            ],
            'update' => [
                'name' => 'Cập nhật bộ sưu tập',
                'list' => ['QL bộ sưu tập', 'Cập nhật bộ sưu tập']
            ],
            'delete' => [
                'name' => 'Xóa bộ sưu tập',
                'list' => ['QL bộ sưu tập', 'Xóa bộ sưu tập']
            ],
            'trash' => [
                'name' => 'Bộ sưu tập đã xóa',
                'list' => ['Bộ sưu tập đã xóa']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [
                'client_asset/custom/css/collection.css',
            ],
            'js' => [
                'admin_asset/library/collection.js',
            ],
            'model' => 'collection'
        ];
    }
}
