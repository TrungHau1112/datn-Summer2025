<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'attribute_id',
    ];

    protected $table = 'product_variant_attribute';

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }

}
