<?php

namespace App\Http\Middleware;

use Closure;

class ProjectPublished
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->route()->parameter('project')->published()) {
            return redirect('home');
        }

        return $next($request);
    }
}
