<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // kiểm ra xem có quyền với đã đăng nhập hay không

        if(Auth::check() && Auth::user()->publish == 1 && count(Auth::user()->getRoleNames()) > 0){
            return $next($request);
        }
        Auth::logout();
        return redirect()->route('auth.index');
    }
}
