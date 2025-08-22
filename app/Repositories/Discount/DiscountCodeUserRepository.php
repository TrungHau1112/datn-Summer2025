<?php  
namespace App\Repositories\Discount;

use App\Models\DiscountCode;
use App\Models\DiscountCodeUser;
use App\Repositories\BaseRepository;


class DiscountCodeUserRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        DiscountCodeUser $model
    ){
        $this->model = $model;
    }

}