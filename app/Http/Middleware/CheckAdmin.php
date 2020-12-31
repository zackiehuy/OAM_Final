<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\ExceptionHandler;
use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (User::isAdmin()) {
            return $next($request);
        } else {
            return ExceptionHandler::notPermission();
        }
    }
}
