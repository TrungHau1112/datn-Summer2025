<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;


class DiscountCode extends Model
{
    use HasFactory, QueryScope;

    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'start_date',
        'end_date',
        'publish',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'discount_code_user');
    }
}
