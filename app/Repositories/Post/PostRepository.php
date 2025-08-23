<?php

namespace App\Repositories\Post;

use App\Repositories\BaseRepository;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        Post $model
    ) {
        $this->model = $model;
    }
}
