<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\ExceptionHandler;
use Closure;
use Illuminate\Http\Request;

class CheckStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (User::isStaff()) {
            return $next($request);
        } else {
            return ExceptionHandler::notPermission();
        }
    }
}
