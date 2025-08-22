<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeUser extends Model
{
    use HasFactory;
    protected $table = 'discount_code_user';
    protected $fillable = ['discount_code_id', 'user_id'];

    
}
