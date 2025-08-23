<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    // như bên province 
    protected $primaryKey = 'code';  //Khai báo khóa chính
    public $incrementing = false;

    public function province() //1 quận /huyện thuộc 1 tỉnh / thành phố
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function wards() //1 quận /huyện có nhiều xã / phường
    {
        return $this->hasMany(Ward::class, 'district_code', 'code');
    }


    // Mối quan hệ với User
    public function users()
    {
        return $this->hasMany(User::class, 'district_id', 'code'); // Giả sử district_id là khóa ngoại trong bảng users
    }
}
