<?php  
namespace App\Repositories\Product;
use App\Repositories\BaseRepository;
use App\Models\ProductVariantAttribute;

class ProductVariantAttributeRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        ProductVariantAttribute $model
    ){
        $this->model = $model;
    }

   
    


}