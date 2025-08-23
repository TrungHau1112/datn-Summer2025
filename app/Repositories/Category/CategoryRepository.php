<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepository;
use App\Models\Category;

class CategoryRepository extends BaseRepository
{

    protected $model;

    public function __construct(
        Category $model
    ) {
        $this->model = $model;
    }
    public function paginationCategory(array $params = [])
    {
        return $this->model
            ->where('is_room', 2)
            ->condition($params['condition'] ?? [])
            ->keyword($params['keyword'] ?? [])
            ->orderBy($params['sort'][0], $params['sort'][1])
            ->paginate($params['perpage']);
    }

    public function paginationRoom(array $params = [])
    {
        return $this->model
            ->where('is_room', 1)
            ->condition($params['condition'] ?? [])
            ->keyword($params['keyword'] ?? [])
            ->orderBy($params['sort'][0], $params['sort'][1])
            ->paginate($params['perpage']);
    }
    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllPublish()
    {
        return $this->model->where('publish', 1)->get();
    }

    public function getCategoryRoom()
    {
        return $this->model->where('publish', 1)->where('is_room', 1)->get();
    }

    public function getCategory()
    {
        return $this->model->where('publish', 1)->where('is_room', 2)->get();
    }
    public function getParentCategory()
    {
        return $this->model->where('publish', 1)->where('is_room', 2)->whereNull('parent_id')->get();
    }
    public function getCategoryById($id)
    {
        return $this->model->find($id);
    }
    public function getCategoryBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }
  

    public function searchCategory($keyword)
    {
        return $this->model->where('name', 'like', '%' . $keyword . '%')->where('publish', 1)->get();
    }
}
