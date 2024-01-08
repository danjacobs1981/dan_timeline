<?php

namespace App\Http\Middleware;

use Closure;

class God
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->god) {
            return $next($request);
        }

        return redirect()->route('dashboard.show')->with('action', 'You are not authorised to view this page')->with('type', 'warning'); // if user is not an admin (god)

    }
}