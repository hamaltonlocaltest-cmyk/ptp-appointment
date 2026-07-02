<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounselorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('counselor')->check()) {
            return redirect()->route('counselor.login');
        }
        return $next($request);
    }
}
