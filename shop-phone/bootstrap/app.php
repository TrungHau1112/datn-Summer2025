<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAuthenticated;
use App\Http\Middleware\UnAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'authenticated' => CheckAuthenticated::class,
            'unauthenticated' => UnAuthenticated::class,
            'checkPermission' => App\Http\Middleware\CheckPermissionVN::class,
            'preventBackHistory' => App\Http\Middleware\RevalidateBackHistory::class,
            'clientAuth' => App\Http\Middleware\ClientAuthMiddleware::class,
            'clientLogin' => App\Http\Middleware\ClientLoginMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
