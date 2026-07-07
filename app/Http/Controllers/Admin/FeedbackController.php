<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentFeedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = AppointmentFeedback::with(['counselee', 'counselor', 'appointment.counselType'])->latest('submitted_at');

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('counselor_id')) {
            $query->where('counselor_id', $request->counselor_id);
        }

        $feedbacks = $query->get();

        $counts = [
            'total'   => AppointmentFeedback::count(),
            'average' => round(AppointmentFeedback::avg('rating'), 1) ?: 0,
            'five'    => AppointmentFeedback::where('rating', 5)->count(),
            'low'     => AppointmentFeedback::where('rating', '<=', 2)->count(),
        ];

        return view('admin.feedback.index', compact('feedbacks', 'counts'));
    }

    public function show(AppointmentFeedback $feedback)
    {
        $feedback->load('counselee', 'counselor', 'appointment.counselType');

        return view('admin.feedback.show', compact('feedback'));
    }
}
