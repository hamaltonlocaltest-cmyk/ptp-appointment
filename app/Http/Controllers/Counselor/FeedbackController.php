<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\AppointmentFeedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Read-only — counselors can see feedback left on their own completed sessions.
    public function index()
    {
        $counselor = Auth::guard('counselor')->user();

        $feedbacks = AppointmentFeedback::with(['appointment.counselType', 'counselee'])
            ->where('counselor_id', $counselor->id)
            ->latest('submitted_at')
            ->get();

        $averageRating = round($feedbacks->avg('rating'), 1);

        return view('counselor.feedback.index', compact('feedbacks', 'averageRating'));
    }
}
