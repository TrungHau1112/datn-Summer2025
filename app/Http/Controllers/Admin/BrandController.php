<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Repositories\Category\CategoryRepository;
use App\Services\Category\CategoryService;
use App\Models\Category;

use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;

class BrandController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Category');
    }
    protected $categoryRepository;
    protected $categoryService;

    function __construct(
        CategoryRepository $categoryRepository,
        CategoryService $categoryService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
    }
    public function index(Request $request)
    {
        $previousUrl = class_basename(url()->current());
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('room');
        return view('admin.pages.brand.index', compact(
            'config'
        ));
    }
    public function getData($request)
    {
        $previousUrl = class_basename(url()->previous());
        $categories = $this->categoryService->paginationRoom($request);
        $config = $this->config();
        return view('admin.pages.brand.components.table', compact('categories', 'config'));
    }
    public function create()
    {
        $categories = Category::all();
        $categoriesChild = Category::with('children')->where('parent_id', null)->where('is_room',2)->get();
        $categoryOptions = $this->categoryService->renderCategoryOptions($categoriesChild);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        return view('admin.pages.brand.save', compact(
            'config',
            'categories',
            'categoryOptions'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:categories,name',
                'publish' => 'required',
            ],
            [
                'name.required' => 'Tên danh mục không được để trống',
                'name.unique' => 'Danh mục đã tồn tại',
                'publish.required' => 'Chưa chọn trạng thái danh mục',
            ]
        );
        if ($this->categoryService->create($request)) {
            return redirect()->route('brand.index')->with('success', 'Tạo danh mục mới thành công');
        }
        return redirect()->route('brand.index')->with('error', 'Tạo danh mục mới thất bại');
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|unique:categories,name, ' . $id,
            ],
            [
                'name.required' => 'Tên danh mục không được để trống',
                'name.unique' => 'Danh mục đã tồn tại',
            ]
        );

        if ($this->categoryService->update($request, $id)) {
            return redirect()->route('brand.index')->with('success', 'Cập nhật danh mục thành công.');
        }
        return redirect()->route('brand.index')->with('error', 'Cập nhật danh mục thất bại');
    }


    public function edit($id)
    {
        $categories = Category::all();
        $category = $this->categoryRepository->findById($id);
        $categoriesChild = Category::with('children')->where('parent_id', null)->where('id', '!=', $id)->where('is_room',2)->get();
        $categoryOptions = $this->categoryService->renderCategoryOptions($categoriesChild, $category);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        return view('admin.pages.brand.save', compact(
            'config',
            'category',
            'categories',
            'categoryOptions'
        ));
    }

    public function delete($id)
    {

        $category = $this->categoryRepository->findById($id);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('delete');
        $config['method'] = 'delete';
        return view('admin.pages.brand.delete', compact(
            'config',
            'category'
        ));
    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->findById($id);
        // dd($category->children()->count());
        if ($category->children()->count() > 0) {
            return redirect()->route('brand.index')->with('error', 'Không thể xóa danh mục này vì nó vẫn còn danh mục con.');
        }
    }

    public function trash()
    {
        $categories = $this->categoryRepository->getOnlyTrashed()->where('is_room', 1);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.brand.trash', compact(
            'config',
            'categories'
        ));
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'category' => [
                'name' => 'Quản lý danh mục',
                'list' => ['Danh mục', 'Danh sách']
            ],
            'room' => [
                'name' => 'Quản lý danh mục thương hiệu',
                'list' => ['Danh mục', 'Danh sách']
            ],
            'create' => [
                'name' => 'Tạo danh mục',
                'list' => ['Danh mục', 'Tạo danh mục']
            ],
            'update' => [
                'name' => 'Cập nhật danh mục',
                'list' => ['Danh mục', 'Cập nhật danh mục']
            ],
            'delete' => [
                'name' => 'Xóa danh mục',
                'list' => ['Danh mục', 'Xóa danh mục']
            ],
            'trash' => [
                'name' => 'Danh mục đã xóa',
                'list' => ['Danh mục', 'Danh sách đã xóa']
            ]
        ];
        return isset($breadcrumb[$key]) ? $breadcrumb[$key] : 'Trang chủ';
    }
    private function config()
    {
        return [
            'css' => [],
            'js' => [],
            'model' => 'brand'
        ];
    }
}
