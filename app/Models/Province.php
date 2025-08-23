<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    // ta xóa cái fillable ;
   
    protected $primaryKey = 'code';  //Khai báo khóa chính
    
    public $incrementing = false; //THêm cái này

    public function districts() //1 tỉnh / thành phố có nhiều quận / huyện
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }

    public function wards() //1 tỉnh / thành phố có nhiều xã / phường
    {
        return $this->hasMany(Ward::class, 'district_code', 'code',);
    }
 
     // Mối quan hệ với User
     public function users()
     {
         return $this->hasMany(User::class, 'province_id', 'code'); // Giả sử province_id là khóa ngoại trong bảng users
     }
    
}
