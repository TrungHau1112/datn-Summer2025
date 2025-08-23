<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionProduct extends Model
{
    use HasFactory;
    protected $table = 'collection_product';
    protected $fillable = [
        'collection_id',
        'product_sku',
        'productVariant_sku',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product', 'collection_id', 'product_sku');
    }
    public function productVariant()
    {
        return $this->belongsToMany(Product::class, 'collection_product', 'collection_id', 'productVariant_sku');
    }
}
