<?php

namespace App\Repositories\Location;

use App\Models\Ward;
use App\Repositories\BaseRepository;

class WardRepository extends BaseRepository
{
    public function __construct(Ward $model)
    {
        parent::__construct($model);
    }

    // Thêm phương thức tùy chỉnh nếu cần
    public function getAllWards()
    {
        return $this->model->all(); // Lấy tất cả các phường/xã
    }

    public function findByDistrictCode($district_code)
    {
        return $this->model->where('district_code', $district_code)->get(); // Lấy phường theo district_code
    }
}
