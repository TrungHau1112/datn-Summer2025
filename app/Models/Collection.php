<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;

class Collection extends Model
{
    use HasFactory, QueryScope;
    
    // protected $table = 'collections';
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'description_text',
        'thumbnail',
        'discount',
        'publish',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $table = 'collections';
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product', 'collection_id', 'product_id');
    }

}
