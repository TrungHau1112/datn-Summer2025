<?php
namespace App\Repositories\Collection;
use App\Repositories\BaseRepository;
use App\Models\CollectionProduct;
class CollectionProductRepository extends BaseRepository
{
    protected $model;
    public function __construct(
        CollectionProduct $model,
    ) {
        $this->model = $model;
    }
}
