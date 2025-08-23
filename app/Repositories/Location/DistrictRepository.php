<?php

namespace App\Repositories\Location;

use App\Models\District;
use App\Repositories\BaseRepository;

class DistrictRepository extends BaseRepository
{
    public function __construct(District $model)
    {
        parent::__construct($model);
    }

    // Thêm phương thức tùy chỉnh nếu cần
    public function getAllDistricts()
    {
        return $this->model->all(); // Lấy tất cả các quận
    }

    public function findDistrictWithWards($id)
    {
        return $this->findById($id, ['wards']); // Lấy quận cùng với danh sách phường/xã
    }

    public function findByProvinceCode($province_code)
    {
        return $this->model->where('province_code', $province_code)->get(); // Lấy quận theo province_code
    }
}
