<?php
namespace App\Repositories\Setting;
use App\Repositories\BaseRepository;
use App\Models\Setting;
use Spatie\Permission\Traits\HasRoles;

class SettingRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        Setting $model
    ) {
        $this->model = $model;
    }

}