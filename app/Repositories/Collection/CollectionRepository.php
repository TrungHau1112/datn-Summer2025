<?php
namespace App\Repositories\Collection;
use App\Repositories\BaseRepository;
use App\Models\Collection;
class CollectionRepository extends BaseRepository
{
    protected $model;
    public function __construct(
        Collection $model,
    ) {
        $this->model = $model;
    }
}
