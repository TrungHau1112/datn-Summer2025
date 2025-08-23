<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepository;
use App\Models\Review;

class ReviewRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        Review $model
    ) {
        $this->model = $model;
    }
}
