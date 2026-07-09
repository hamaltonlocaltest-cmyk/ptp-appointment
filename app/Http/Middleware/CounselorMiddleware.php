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

        if (Auth::guard('counselor')->user()->status === 'deleted') {
            Auth::guard('counselor')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('counselor.login')
                ->withErrors(['email' => 'This account has been deleted. Please contact the administrator.']);
        }

        return $next($request);
    }
}
