<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounseleeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('counselee')->check()) {
            return redirect()->route('counselee.login');
        }
        return $next($request);
    }
}
