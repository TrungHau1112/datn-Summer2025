<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;


class CheckPermissionVN
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $model = $request->route('model');
        $currentAction = explode('Controller@', class_basename(Route::currentRouteAction()));
        $method = preg_split('/(?=[A-Z])/', $currentAction[1]);
        // $action = $method[0] === 'store' ? 'create' : ($method[0] === 'update' ? 'edit' : $method[0]);
        $action = match ($method[0]) {
            'store' => 'create',
            'update' => 'edit',
            'show', 'get' => 'index',
            'restore', 'destroy' => 'delete',
            default => $method[0],
        };
        $model = ucfirst($model);
        $permission = $model != null ? $permission = $model . ' ' .  $action : $permission = $currentAction[0] . ' ' . $action;
        if (Auth::user()->hasRole('Super Admin')) {
            return $next($request);
        }
        if (!Auth::user()->hasPermissionTo($permission)) {
            abort(403, 'Đủ trình không ʕ•ᴥ•ʔ | Không có quyền: ' . $permission);
        }
        return $next($request);
    }

}
