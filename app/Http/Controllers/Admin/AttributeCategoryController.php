<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Services\Attribute\AttributeCategoryService;
use App\Repositories\Attribute\AttributeCategoryRepository;
use App\Http\Requests\Attribute\UpdateAttributeRequest;

use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
class AttributeCategoryController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('AttributeCategory');
    }
    protected $attributeCategoryService;
    protected $attributeCategoryRepository;
    function __construct(
        AttributeCategoryService $attributeCategoryService,
        AttributeCategoryRepository $attributeCategoryRepository
    ) {
        $this->attributeCategoryService = $attributeCategoryService;
        $this->attributeCategoryRepository = $attributeCategoryRepository;
    }

    public function index(Request $request)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        $attributes = $this->attributeCategoryService->paginate($request);
        return view('admin.pages.product.attribute.index', compact(
            'config',
            'attributes'
        ));
    }
    public function getData($request)
    {
        $attributes = $this->attributeCategoryService->paginate($request);
        $config = $this->config();
        return view('admin.pages.product.attribute.components.table', compact('attributes', 'config'));
    }
    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        return view('admin.pages.product.attribute.save', compact(
            'config'
        ));
    }

    public function store(StoreAttributeRequest $request)
    {

        if ($this->attributeCategoryService->create($request)) {
            return redirect()->route('attributeCategory.index')->with('success', 'Tạo mới thuộc tính thành công');
        }
        return redirect()->back()->with('error', 'Tạo mới thuộc tính thất bại');
    }

    public function edit($id)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        $attribute = $this->attributeCategoryRepository->findById($id);
        return view('admin.pages.product.attribute.save', compact(
            'config',
            'attribute'
        ));
    }

    public function update(UpdateAttributeRequest $request, $id)
    {
        // $this->attributeCategoryService->update($request, $id);
        if ($this->attributeCategoryService->update($request, $id)) {
            return redirect()->route('attributeCategory.index')->with('success', 'Cập nhật thuộc tính thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật thuộc tính thất bại');
    }

    public function destroy($id)
    {
        if (Attribute::find($id)->delete()) {
            return successResponse();
        }
    }

    public function trash()
    {
        $attributes = $this->attributeCategoryRepository->getOnlyTrashed();
        // dd($attributes);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.product.attribute.trash', compact(
            'config',
            'attributes'
        ));
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Quản lý thuộc tính',
                'list' => ['QL thuộc tính', 'Danh sách']
            ],
            'create' => [
                'name' => 'Tạo thuộc tính',
                'list' => ['QL thuộc tính', 'Tạo thuộc tính chi đó']
            ],
            'update' => [
                'name' => 'Cập nhật thuộc tính',
                'list' => ['QL thuộc tính', 'Cập nhật thuộc tính']
            ],
            'delete' => [
                'name' => 'Xóa thuộc tính',
                'list' => ['QL thuộc tính', 'Xóa thuộc tính']
            ],
            'trash' => [
                'name' => 'Danh sách thuộc tính đã xóa',
                'list' => ['QL thuộc tính', 'Danh sách đã xóa']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [
            ],
            'js' => [
                'admin_asset/library/attribute.js'
            ],
            'model' => 'attributeCategory',
        ];
    }
}
