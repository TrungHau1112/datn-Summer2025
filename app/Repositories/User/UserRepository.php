<?php  
namespace App\Repositories\User;
use App\Repositories\BaseRepository;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class UserRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        User $model
    ){
        $this->model = $model;
    }

    public function paginationCustomer(array $params = []){
        return $this->model
                    ->condition($params['condition'] ?? [])
                    ->keyword($params['keyword'] ?? [])
                    ->orderBy($params['sort'][0], $params['sort'][1])
                    ->whereDoesntHave('roles')
                    ->paginate($params['perpage']);
                    
    }

    public function paginationAdmin(array $params = []){
        return $this->model
                    ->condition($params['condition'] ?? [])
                    ->keyword($params['keyword'] ?? [])
                    ->orderBy($params['sort'][0], $params['sort'][1])
                    ->whereHas('roles')
                    ->paginate($params['perpage']);       
    }

    public function totalCustomer(){
        return $this->model->whereDoesntHave('roles')->count();
    }


}