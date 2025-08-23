<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepository;
use App\Models\ForbiddenWord;

class ForbiddenWordRepository extends BaseRepository
{

    protected $model;

    public function __construct( 
        ForbiddenWord $model
    ) {
        $this->model = $model;
    }
}
