<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Staff\StoreStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Services\User\UserService;
use App\Repositories\User\UserRepository;
use App\Repositories\Location\ProvinceRepository;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;

class StaffController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Staff');
    }
    protected $userService;
    protected $userRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;
    public function __construct(
        UserService $userService,
        UserRepository $userRepository,
        ProvinceRepository $provinceRepository,
    ) {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function index(Request $request)
    {
        $previousUrl = class_basename(url()->current());
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('admin');
        return view('admin.pages.staff.index', compact(
            'config',
        ));
    }
    public function getData($request)
    {
        $previousUrl = class_basename(url()->previous());
        $users = $this->userService->paginationAdmin($request);
        $config = $this->config();

        return view('admin.pages.staff.components.table', compact('users', 'config'));
    }

    public function create()
    {
        $provinces = $this->provinceRepository->getAllProvinces(); // Lấy danh sách tỉnh
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        $roles = Role::all();
        return view('admin.pages.staff.save', compact(
            'config',
            'provinces',
            'roles'
        ));
    }

    public function store(StoreStaffRequest $request)
    {
        // Tạo người dùng mới
        $user = $this->userService->create($request);
        if ($user) {
            return redirect()->route('staff.index')->with('success', 'Tạo người dùng mới thành công');
        }
        return redirect()->route('staff.index')->with('error', 'Tạo người dùng mới thất bại');
    }

    public function update(UpdateStaffRequest $request, $id)
    {
        
        $user = $this->userService->update($request, $id);
        if ($user) {
            return redirect()->route('staff.index', ['page' => $request->page])->with('success', 'Cập nhật người dùng thành công.');
        }
        return redirect()->route('staff.index')->with('error', 'Cập nhật người dùng thất bại');
    }

    public function edit($id)
    {
        // $user = $this->userRepository->findById($id); // 1 dòng thôi
        $user = $this->userRepository->findById($id, ['wishlists.product', 'orders']);
        // $user  = $this->userRepository->findById($id, ['province', 'district', 'ward']);
        $provinces = $this->provinceRepository->getAllProvinces(); // Lấy danh sách tỉnh
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        $roles = Role::all();
        return view('admin.pages.staff.save', compact(
            'config',
            'user',
            'provinces',
            'roles'
        ));
    }

    public function delete($id)
    {
        $user = $this->userRepository->findById($id);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('delete');
        $config['method'] = 'delete';
        return view('admin.pages.staff.delete', compact(
            'config',
            'user'
        ));
    }

    public function destroy($id)
    {
        $config['model'] = 'user';
        if ($this->userService->delete($id)) {
            return redirect()->route('staff.index')->with('success', 'Xóa người dùng thành công');
        }
        return redirect()->route('staff.index')->with('error', 'Xóa người dùng thất bại');
    }

    public function getDistricts($province_code)
    {
        $districts = $this->districtRepository->findByProvinceCode($province_code);
        return response()->json($districts);
    }

    public function getWards($district_code)
    {
        // Lấy danh sách phường/xã theo mã quận/huyện
        $wards = $this->wardRepository->findByDistrictCode($district_code);

        // Kiểm tra nếu không tìm thấy phường/xã
        if ($wards->isEmpty()) {
            return response()->json([]); // Trả về mảng rỗng nếu không tìm thấy
        }

        return response()->json($wards);
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'customer' => [
                'name' => 'Danh sách khách hàng',
                'list' => ['Danh sách khách hàng']
            ],
            'admin' => [
                'name' => 'Danh sách nhân viên',
                'list' => ['Danh sách nhân viên']
            ],
            'create' => [
                'name' => 'Tạo người dùng',
                'list' => ['QL người dùng', 'Tạo người dùng']
            ],
            'update' => [
                'name' => 'Cập nhật người dùng',
                'list' => ['QL người dùng', 'Cập nhật người dùng']
            ],
            'delete' => [
                'name' => 'Xóa người dùng',
                'list' => ['QL người dùng', 'Xóa người dùng']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [],
            'js' => [
                'admin_asset/library/location.js'
            ],
            'model' => 'staff'
        ];
    }
}
