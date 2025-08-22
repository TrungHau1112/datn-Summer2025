<?php
namespace App\Repositories\Payment;
use App\Repositories\BaseRepository;
use App\Models\PaymentMethod;

class PaymentMethodRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        PaymentMethod $model
    ) {
        $this->model = $model;
    }

    public function getAllPublic()
    {
        return $this->model->where('publish', 1)->get();
    }

}