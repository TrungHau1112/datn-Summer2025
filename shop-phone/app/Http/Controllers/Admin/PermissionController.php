<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Permission\PermissionService;
use App\Repositories\Permission\PermissionRepository;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Permission');
    }
    protected $permissionService;
    protected $permissionRepository;
    function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    ) {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        $permissions = $this->permissionService->paginate($request);
        $roles = Role::all();
        return view('admin.pages.permission.index', compact(
            'config',
            'permissions',
            'roles'
        ));
    }
    public function getData($request)
    {
        $permissions = $this->permissionService->paginate($request);
        $config = $this->config();
        $roles = Role::all();
        return view('admin.pages.permission.components.table', compact('permissions', 'config', 'roles'));
    }
    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        return view('admin.pages.permission.save', compact(
            'config'
        ));
    }

    public function store(StorePermissionRequest $request)
    {
        $permissions = Permission::all();
        $allPermission = [];
        foreach ($permissions->toArray() as $permission) {
            $allPermission[] = $permission['name'];
        }
        if ($allPermission == []) {
            foreach (getActionRoute() as $newPermission) {
                Permission::create(['name' => $newPermission]);
            }
        } else {
            $lostPermissions = array_diff(getActionRoute(), $allPermission);
            foreach ($lostPermissions as $newPermission) {
                Permission::create(['name' => $newPermission]);
            }
        }

        return redirect()->back()->with('success', 'Làm mới thành công');
    }

    // public function edit($id)
    // {
    //     $config = $this->config();
    //     $config['breadcrumb'] = $this->breadcrumb('update');
    //     $config['method'] = 'edit';
    //     $permission = $this->permissionRepository->findById($id);
    //     return view('admin.pages.permission.save', compact(
    //         'config',
    //         'permission'
    //     ));
    // }

    public function edit(UpdatePermissionRequest $request)
    {
        $role = Role::find($request->roleId);
        $permission = $request->permissionName;
        if ($request->is_checked == 'checked') {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }
        return successResponse();
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Quản lý phân quyền',
                'list' => ['QL phân quyền', 'Danh sách']
            ],
            'create' => [
                'name' => 'Tạo phân quyền',
                'list' => ['QL phân quyền', 'Tạo phân quyền chi đó']
            ],
            'update' => [
                'name' => 'Cập nhật phân quyền',
                'list' => ['QL phân quyền', 'Cập nhật phân quyền']
            ],
            'delete' => [
                'name' => 'Xóa phân quyền',
                'list' => ['QL phân quyền', 'Xóa phân quyền']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [],
            'js' => [],
            'model' => 'permission'
        ];
    }
}
