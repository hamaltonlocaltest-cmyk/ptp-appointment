<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $counselor = Auth::guard('counselor')->user();
        return view('counselor.dashboard.index', compact('counselor'));
    }
}