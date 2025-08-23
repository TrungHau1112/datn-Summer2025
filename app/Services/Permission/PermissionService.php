<?php
namespace App\Services\Permission;
use App\Services\BaseService;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;



class PermissionService extends BaseService
{

    protected $permissionRepository;

    public function __construct(
        PermissionRepository $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
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
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $users = $this->permissionRepository->pagination($agruments);
        return $users;
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', 're_password']);
            $payload['password'] = Hash::make($request->password);
            $user = $this->permissionRepository->create($payload);
            //lỗi ở đây
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
            $user = $this->permissionRepository->update($id, $payload);
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
            $this->permissionRepository->delete($id);
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