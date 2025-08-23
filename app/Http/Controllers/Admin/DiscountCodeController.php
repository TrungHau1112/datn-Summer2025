<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Discount\DiscountCodeService;
use App\Repositories\Discount\DiscountCodeRepository;
use App\Http\Requests\Discount\UpdateDiscountCodeRequest;
use App\Http\Requests\Discount\StoreDiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Support\Carbon;

use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
class DiscountCodeController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('DiscountCode');
    }
    protected $discountCodeService;
    protected $discountCodeRepository;

    public function __construct(
        DiscountCodeService $discountCodeService,
        DiscountCodeRepository $discountCodeRepository
    ) {
        $this->discountCodeService = $discountCodeService;
        $this->discountCodeRepository = $discountCodeRepository;
    }

    /**
     * Hiển thị danh sách mã giảm giá
     */
    public function index(Request $request)
    {
        $discountCodes = $this->discountCodeService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.discount.index', compact('discountCodes', 'config'));
    }

    public function getData($request)
    {
        $discountCodes = $this->discountCodeService->paginate($request);
        $config = $this->config();
        return view('admin.pages.discount.components.table', compact('discountCodes', 'config'));
    }
    /**
     * Hiển thị form tạo mới mã giảm giá
     */
    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        return view('admin.pages.discount.save', compact('config'));
    }

    /**
     * Lưu mã giảm giá mới
     */
    public function store(StoreDiscountCodeRequest $request)
    {
        if ($this->discountCodeService->create($request)) {
            return redirect()->route('discountCode.index')->with('success', 'Mã giảm giá đã được tạo thành công!');
        }
        return redirect()->route('discountCode.index')->with('error', 'Tạo mã giảm giá thất bại!');
    }

    /**
     * Hiển thị form chỉnh sửa mã giảm giá
     */
    public function edit($id)
    {
        $discountCode = $this->discountCodeRepository->findById($id);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        $discountCode->start_date = changeDateFormat($discountCode->start_date);
        $discountCode->end_date = changeDateFormat($discountCode->end_date);

        return view('admin.pages.discount.save', compact('discountCode', 'config')); //truyền discountCode 
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update(UpdateDiscountCodeRequest $request, $id)
    {
        if ($this->discountCodeService->update($request, $id)) {
            return redirect()->route('discountCode.index')->with('success', 'Mã giảm giá đã được cập nhật thành công!');
        }
        return redirect()->route('discountCode.index')->with('error', 'Cập nhật mã giảm giá thất bại!');
    }

    /**
     * Xóa mã giảm giá
     */

    public function delete($id)
    {
        $discountCode = $this->discountCodeRepository->findById($id);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('delete');
        $config['method'] = 'delete';
        return view('admin.pages.discount.delete', compact(
            'config',
            'discountCode'
        ));
    }
    public function destroy($id)
    {
        if ($this->discountCodeService->delete($id)) {
            return redirect()->route('discountCode.index')->with('success', 'Mã giảm giá đã được xóa thành công!');
        }
        return redirect()->route('discountCode.index')->with('error', 'Xóa mã giảm giá thất bại!');
    }

    public function trash()
    {
        $discountCodes = $this->discountCodeRepository->getOnlyTrashed();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.discount.trash', compact(
            'config',
            'discountCodes'
        ));
    }

    /**
     * Xây dựng breadcrumb cho các hành động trong controller
     */
    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Quản lý mã giảm giá',
                'list' => ['QL mã giảm giá']
            ],
            'create' => [
                'name' => 'Tạo mã giảm giá',
                'list' => ['QL mã giảm giá', 'Tạo mã giảm giá']
            ],
            'update' => [
                'name' => 'Cập nhật mã giảm giá',
                'list' => ['QL mã giảm giá', 'Cập nhật mã giảm giá']
            ],
            'delete' => [
                'name' => 'Xóa mã giảm giá',
                'list' => ['QL mã giảm giá', 'Xóa mã giảm giá']
            ],
            'trash' => [
                'name' => 'Mã giảm giá đã xóa',
                'list' => ['QL mã giảm giá', 'Mã giảm giá đã xóa']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [],
            'js' => [],
            'model' => 'discountCode' //Config chỗ ni DiscountCodeController thì là discountCode 
        ];
    }
}
