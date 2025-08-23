<?php  
namespace App\Repositories\Order;
use App\Repositories\BaseRepository;
use App\Models\OrderDetail;

class OrderDetailsRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        OrderDetail $model
    ){
        $this->model = $model;
    }

}