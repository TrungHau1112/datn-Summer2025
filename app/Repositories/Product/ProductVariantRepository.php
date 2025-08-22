<?php
namespace App\Repositories\Product;
use App\Repositories\BaseRepository;
use App\Models\ProductVariant;
use DB;
class ProductVariantRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        ProductVariant $model
    ) {
        $this->model = $model;
    }

    public function findVariant($product_id, $code)
    {
        return $this->model->where('product_id', $product_id)
            ->where('code', 'REGEXP', '(^|,)' . $code . '(,|$)')
            ->first();
    }


    public function updateQuantity($product_id, $quantity)
    {
        return $this->model->where([
            ['product_id', $product_id],
        ])->update(['quantity' => DB::raw('quantity - ' . $quantity)]);
    }








}