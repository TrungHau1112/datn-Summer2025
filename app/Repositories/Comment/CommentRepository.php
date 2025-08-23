<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepository;
use App\Models\Comment;

class CommentRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        Comment $model
    ) {
        $this->model = $model;
    }
    public function paginationComment(array $params = [])
    {
        return $this->model
            // ->whereNull('deleted_at')
            ->condition($params['condition'] ?? [])
            ->keyword($params['keyword'] ?? [])
            ->orderBy($params['sort'][0], $params['sort'][1])
            ->paginate($params['perpage']);
    }
    public function getLimitComments($column, $values = [], $limit = 10, $relation = [], $select = ['*'])
    {

        $query = $this->model->select($select)
            ->whereIn($column, $values)
            ->orderBy('created_at', 'desc')
            ->whereNull('parent_id')
            ->take($limit)
            ->with($relation);
        // Kiểm tra SQL Query
        \Log::info($query->toSql());

        return $query->get();
    }
    public function getLimitReplies($column, $values = [], $limit = 10, $relation = [], $select = ['*'])
    {
        $query = $this->model->select($select)
            ->whereIn($column, $values)
            ->orderBy('created_at', 'desc')
            ->whereNotNull('parent_id')
            ->take($limit)
            ->with($relation);
        // Kiểm tra SQL Query
        \Log::info($query->toSql());

        return $query->get();
    }
    public function getChilds($id)
    {
        $query = $this->model->where('parent_id', $id);
        \Log::info($query->toSql());
        return $query;
    }
}
