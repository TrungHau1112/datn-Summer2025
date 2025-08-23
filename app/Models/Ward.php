<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $table = 'wards';
    // như province 
    protected $primaryKey = 'code';  //Khai báo khóa chính
    public $incrementing = false;

    public function district() //1 xã / phường thuộc 1 quận / huyện
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
    
     // Mối quan hệ với User
     public function users()
     {
         return $this->hasMany(User::class, 'ward_id', 'code'); // Giả sử ward_id là khóa ngoại trong bảng users
     }
}
