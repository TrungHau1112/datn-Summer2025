<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;


class BaseRepository
{

    protected $model;

    public function __construct(
        Model $model
    ) {
        $this->model = $model;
    }

    public function pagination(array $params = [])
    {

        return $this->model
            ->condition($params['condition'] ?? [])
            ->keyword($params['keyword'] ?? [])
            ->orderBy($params['sort'][0], $params['sort'][1])
            ->paginate($params['perpage']);
    }


    public function create(array $payload = [])
    {
        return $this->model->create($payload);
    }

    public function insert(array $payload = [])
    {
        return $this->model->insert($payload);
    }

    public function update(int $id, array $payload = [])
    {
        return $this->findById($id)->fill($payload)->save();
    }

    public function updateOrCreate(array $where = [], array $payload = [])
    {
        return $this->model->updateOrCreate($where, $payload);
    }

    public function findById(int $id, array $relation = [], array $select = ['*'])
    {
        return $this->model->select($select)->with($relation)->find($id);
    }


    public function findByField(string $field, $value, array $select = ['*'], $relation = [])
    {
        return $this->model->select($select)->where($field, $value)->with($relation);
    }

    public function findByWhereIn($column, $values = [], $relation = [], $select = ['*'])
    {
        return $this->model->select($select)->whereIn($column, $values)->with($relation)->get();
    }


    public function delete(int $id)
    {
        return $this->findById($id)->delete();
    }

    public function updateByWhereIn(string $whereInField = '', array $whereIn = [], array $payload = [])
    {
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    public function deleteByWhereIn(string $whereInField, array $whereIn = [])
    {
        return $this->model->whereIn($whereInField, $whereIn)->delete();
    }


    public function getAll()
    {
        return $this->model->all();
    }


    public function deleteManyWhere($field1, $valField1, $field2, $valField2)
    {
        return $this->model->where($field1, $valField1)->where($field2, $valField2)->delete();
    }


    public function createMany(array $payload = [])
    {
        return $this->model->insert($payload);
    }

    public function getOnlyTrashed()
    {
        // return $this->model->onlyTrashed()->get();
        return $this->model->whereNotNull('deleted_at')->get();
    }

    public function restore(int $id)
    {
        // return $this->getOnlyTrashed()->find($id)->restore();
        return $this->update($id, ['deleted_at' => null]);

    }

    public function destroy(int $id)
    {
        // return $this->getOnlyTrashed()->find($id)->forceDelete();
        return $this->delete($id);
    }


}
