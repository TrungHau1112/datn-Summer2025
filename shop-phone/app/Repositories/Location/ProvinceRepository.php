<?php

namespace App\Repositories\Location;

use App\Models\Province;
use App\Repositories\BaseRepository;

class ProvinceRepository extends BaseRepository
{
    public function __construct(Province $model)
    {
        parent::__construct($model);
    }

    // Thêm phương thức tùy chỉnh nếu cần
    public function getAllProvinces()
    {
        return $this->model->all(); // Lấy tất cả các tỉnh
    }

    public function findProvinceWithDistricts($id)
    {
        return $this->findById($id, ['districts']); // Lấy tỉnh cùng với danh sách quận
    }
}
