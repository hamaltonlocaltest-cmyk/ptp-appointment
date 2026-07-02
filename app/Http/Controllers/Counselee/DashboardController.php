<?php

namespace App\Http\Controllers\Counselee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $counselee = Auth::guard('counselee')->user();
        $counselee->load('counselTypes');

        $appointments = Appointment::where('counselee_id', $counselee->id)->get();

        $counts = [
            'total'     => $appointments->count(),
            'pending'   => $appointments->where('status', 'pending')->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
        ];

        $recentAppointments = Appointment::with(['counselor', 'counselType'])
            ->where('counselee_id', $counselee->id)
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->take(5)
            ->get();

        $nextAppointment = Appointment::with(['counselor', 'counselType'])
            ->where('counselee_id', $counselee->id)
            ->upcoming()
            ->first();

        return view('counselee.dashboard.index', compact(
            'counselee', 'counts', 'recentAppointments', 'nextAppointment'
        ));
    }
}