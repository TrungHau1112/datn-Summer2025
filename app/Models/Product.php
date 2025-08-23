<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;
class Product extends Model
{
    use HasFactory, QueryScope;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'short_content',
        'description',
        'quantity',
        'price',
        'discount',
        'thumbnail',
        'albums',
        'publish',
        'view',
        'is_featured',
        'has_attribute',
        'attribute_category',
        'attribute',
        'variant',
        'meta_title',
        'meta_description',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'attribute' => 'json',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }


    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_product', 'product_id', 'collection_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product', 'product_id', 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    public function getPriceAttrAttribute()
    {
        $minPrice = $this->productVariants->min('price');
        $maxPrice = $this->productVariants->max('price');
        return (object) [
            'min' => $minPrice,
            'max' => $maxPrice,
        ];
    }



}
