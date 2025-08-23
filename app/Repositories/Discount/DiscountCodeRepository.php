<?php  
namespace App\Repositories\Discount;

use App\Models\DiscountCode;
use App\Repositories\BaseRepository;


class DiscountCodeRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        DiscountCode $model
    ){
        $this->model = $model;
    }

}