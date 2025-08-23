<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;

class ProductVariant extends Model
{
    use HasFactory, QueryScope;

    protected $fillable = [
         'sku', 
         'code',
         'title',
         'price', 
         'quantity', 
         'thumbnail',
         'albums',
         'publish',
         'product_id',
    ];

    protected $table = 'product_variants';


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_variant_attribute', 'product_variant_id', 'attribute_id');
    }

    public function productVariantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'product_variant_id', 'id');
    }
}
