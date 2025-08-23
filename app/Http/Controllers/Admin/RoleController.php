<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Role\RoleService;
use App\Repositories\Role\RoleRepository;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Role'); 
    }
    protected $roleService;
    protected $roleRepository;
    function __construct(
        RoleService $roleService,
        RoleRepository $roleRepository
    ) {
        $this->roleService = $roleService;
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $request)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        $roles = $this->roleService->paginate($request);
        $roles = Role::all();
        return view('admin.pages.role.index', compact(
            'config',
            'roles',
            'roles'
        ));
    }

    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $permissions = Permission::all()->sortBy('name');

        $config['method'] = 'create';
        $config['model'] = 'role';
        return view('admin.pages.permission.role.save', compact(
            'config',
            'permissions',
        ));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->create($request);
        $this->roleService->givePermissionTo($request);
        return to_route('permission.index')->with('success', 'Tạo vai trò mới thành công');
    }

    public function edit($id)
    {
        $config = $this->config();
        $role = $this->roleRepository->findById($id);
        $permissions = Permission::all()->sortBy('name');

        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        return view('admin.pages.permission.role.save', compact(
            'config',
            'role',
            'permissions'
        ));
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->roleService->update($request, $id);
        $this->roleService->givePermissionTo($request);
        return to_route('permission.index')->with('success', 'Cập nhật vai trò thành công');
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Quản lý vai trò',
                'list' => ['QL vai trò', 'Danh sách']
            ],
            'create' => [
                'name' => 'Tạo vai trò',
                'list' => ['QL vai trò', 'Tạo vai trò chi đó']
            ],
            'update' => [
                'name' => 'Cập nhật vai trò',
                'list' => ['QL vai trò', 'Cập nhật vai trò']
            ],
            'delete' => [
                'name' => 'Xóa vai trò',
                'list' => ['QL vai trò', 'Xóa vai trò']
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
            ],
            'model' => 'role'
        ];
    }
}
