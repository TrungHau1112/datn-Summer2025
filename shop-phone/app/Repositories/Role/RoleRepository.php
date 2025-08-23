<?php  
namespace App\Repositories\Role;
use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        Role $model
    ){
        $this->model = $model;
    }

}