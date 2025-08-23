<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;

class Review extends Model
{
    use HasFactory, QueryScope;
    protected $fillable = [
        'product_id',
        'user_id',
        'parent_id',
        'content',
        'rating',
        'albums'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // get review children
    public function children()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }
}
