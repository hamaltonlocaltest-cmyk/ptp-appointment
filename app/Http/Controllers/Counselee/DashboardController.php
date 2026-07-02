<?php

namespace App\Http\Controllers\Counselee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $counselee = Auth::guard('counselee')->user();
        return view('counselee.dashboard.index', compact('counselee'));
    }
}