<?php

namespace Http\Middleware;

use Closure;
use Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (! Auth::user()->can($permission)) {
            return abort(403);
        }
        return $next($request);
    }
}
