<?php
namespace App\Repositories\Permission;
use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        Permission $model
    ) {
        $this->model = $model;
    }

}