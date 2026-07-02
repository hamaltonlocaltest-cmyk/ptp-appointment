<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $counselor = Auth::guard('counselor')->user();

        $stats = [
            'total'     => Appointment::where('counselor_id', $counselor->id)->count(),
            'confirmed' => Appointment::where('counselor_id', $counselor->id)->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('counselor_id', $counselor->id)->where('status', 'completed')->count(),
        ];

        $upcoming = Appointment::with(['counselee', 'counselType'])
            ->where('counselor_id', $counselor->id)
            ->upcoming()
            ->take(5)
            ->get();

        return view('counselor.dashboard.index', compact('counselor', 'stats', 'upcoming'));
    }
}