<?php
namespace App\Traits;

trait QueryScope
{

    public function __construct()
    {

    }

    public function scopeCondition($query, $condition = [])
    {
        if (isset($condition) && is_array($condition) && count($condition)) {
            foreach ($condition as $key => $val) {
                if ($val !== 0) {
                    $query->where($key, '=', $val);
                }
            }
        }
        return $query;
    }

    public function scopeKeyword($query, $keyword = [])
    {
        if (isset($keyword) && is_array($keyword) && count($keyword) && !empty($keyword['search'])) {
            if (isset($keyword['field']) && count($keyword['field'])) {
                $query->where(function ($subQuery) use ($keyword) {
                    foreach ($keyword['field'] as $key => $val) {
                        $subQuery->orWhere($val, 'LIKE', '%' . $keyword['search'] . '%');
                    }
                });
            } else {
                $query->where('name', 'LIKE', '%' . $keyword['search'] . '%');
            }
        }
        return $query;
    }


    public function scopeRelation($query, $relations = [], $model)
    {
        $query = $model::query();
        if (isset($relations) && is_array($relations) && count($relations) && !empty($relations)) {
            foreach ($relations as $key => $val) {
                if (isset($relations[$key]) && count($relations[$key]) && !empty($relations[$key])) {
                    foreach ($relations[$key] as $categoryId) {
                        $query->whereHas($key, function ($query) use ($categoryId) {
                            $query->where('id', $categoryId);
                        });
                    }
                }
            }
        }
        return $query;
    }

}