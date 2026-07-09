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

        if (Auth::guard('counselee')->user()->status === 'deleted') {
            Auth::guard('counselee')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('counselee.login')
                ->withErrors(['email' => 'This account has been deleted. Please contact the administrator.']);
        }

        return $next($request);
    }
}
