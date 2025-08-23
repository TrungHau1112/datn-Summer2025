<?php
namespace App\Traits;

use Illuminate\Routing\Controllers\Middleware;

trait HasDynamicMiddleware
{
    public static function getMiddleware(string $model): array
    {
        return [
            new Middleware("permission:{$model} index", only: ['index']),
            new Middleware("permission:{$model} create", only: ['create']),
            new Middleware("permission:{$model} edit", only: ['edit']),
            new Middleware("permission:{$model} delete", only: ['delete']),
            // new Middleware("permission:{$model} update", only: ['update']),
        ];
    }
}

