<?php
namespace App\Services\Role;
use App\Services\BaseService;
use App\Repositories\Role\RoleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleService extends BaseService
{

    protected $roleRepository;

    public function __construct(
        RoleRepository $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }


    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request->input('keyword'),
                'field' => ['name', 'description']
            ],
            'condition' => [
                'publish' => $request->integer('publish'),
                'user_catalogue_id' => $request->integer('user_catalogue_id'),
            ],
            'sort' => $request->input('sort')
                ? array_map('trim', explode(',', $request->input('sort')))
                : ['id', 'desc'],
            'perpage' => $request->integer('perpage') ?? 20,
        ];
    }

    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        // dd($agruments);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));

        $users = $this->roleRepository->pagination($agruments);
        return $users;
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $this->roleRepository->create(['name' => $payload['name']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            // $this->log($e);
            // return false;
        }
    }
    public function givePermissionTo($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $role = Role::findByName($payload['name']);
            if (!isset($payload['permission'])) {
                $role->syncPermissions([]);
            } else {
                $role->syncPermissions($payload['permission']);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            // $this->log($e);
            // return false;
        }
    }
    public function update($request, $id)
    {

        // thêm nhiều dữ liệu vào nhiều bảng khác nhau
        // ví dụ : tạo mới user và gắn quyền cho hắn 
        // tạo user xong rồi mà gắn quyền hắn lỗi trất
        // User::create($data); chạy oke
        // Role::create($user); chạy lỗi mọe trất
        // Là thằn user nó được tạo trong bảng Users rồi nhưng trong bảng Roles thì chưa có quyền của thằn user đó 
        // => lỗi :v, mất dữ liệu
        // => giải pháp : dùng transaction => Đảm bảo toàn vẹn dữ liệu
        // đang chạy trong 1 transaction, nếu có lỗi thì rollback lại hết (reset lại như chưa từng chạy)
        // Try Catch để bắt lỗi
        // Bất kể lỗi gì xảy ra trong try thì nó sẽ tự động chạy vào catch
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method']);
            $user = $this->roleRepository->update($id, $payload);
            // User::create($data); chạy oke
            // Role::create($user); chạy lỗi mọe trất
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->roleRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }



}