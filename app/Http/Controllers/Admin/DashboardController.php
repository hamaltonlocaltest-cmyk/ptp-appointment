<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Counselor;
use App\Models\Counselee;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [  // resources/views/admin/dashboard/index.blade.php
            'total_admins'       => User::count(),
            'total_counselors'   => Counselor::count(),
            'total_counselees'   => Counselee::count(),
            'pending_counselors' => Counselor::where('status', 'pending')->count(),
            'recent_counselors'  => Counselor::latest()->take(5)->get(),
            'recent_counselees'  => Counselee::latest()->take(5)->get(),
        ]);
    }
}