<?php
// ============================================================
// FILE 1: app/Http/Middleware/SuperAdminMiddleware.php
// ============================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}


// ============================================================
// FILE 2: app/Http/Middleware/CounselorMiddleware.php
// ============================================================
// namespace App\Http\Middleware;
//
// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
//
// class CounselorMiddleware
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if (!Auth::guard('counselor')->check()) {
//             return redirect()->route('counselor.login');
//         }
//         return $next($request);
//     }
// }


// ============================================================
// FILE 3: app/Http/Middleware/CounseleeMiddleware.php
// ============================================================
// namespace App\Http\Middleware;
//
// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
//
// class CounseleeMiddleware
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if (!Auth::guard('counselee')->check()) {
//             return redirect()->route('counselee.login');
//         }
//         return $next($request);
//     }
// }
