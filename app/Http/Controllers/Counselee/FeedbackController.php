<?php

namespace App\Http\Controllers\Counselee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentFeedback;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function index()
    {
        $counselee = Auth::guard('counselee')->user();

        $feedbacks = AppointmentFeedback::with(['appointment.counselType', 'counselor'])
            ->where('counselee_id', $counselee->id)
            ->latest('submitted_at')
            ->get();

        return view('counselee.feedback.index', compact('feedbacks'));
    }

    public function create(Appointment $appointment)
    {
        if ($blocked = $this->guard($appointment)) {
            return $blocked;
        }

        $appointment->load('counselor', 'counselType');

        return view('counselee.appointments.feedback', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($blocked = $this->guard($appointment)) {
            return $blocked;
        }

        $validated = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $feedback = AppointmentFeedback::create([
            'appointment_id' => $appointment->id,
            'counselee_id'   => $appointment->counselee_id,
            'counselor_id'   => $appointment->counselor_id,
            'rating'         => $validated['rating'],
            'comments'       => $validated['comments'] ?? null,
            'submitted_at'   => now(),
        ]);

        $this->notifications->notifyFeedbackReceived($feedback);

        return redirect()->route('counselee.appointments.index')
            ->with('success', 'Thank you for your feedback!');
    }

    /**
     * Returns a redirect response if feedback isn't allowed for this
     * appointment/counselee, or null if it's fine to proceed.
     */
    private function guard(Appointment $appointment): ?RedirectResponse
    {
        $counselee = Auth::guard('counselee')->user();

        if ($appointment->counselee_id !== $counselee->id) {
            abort(403);
        }

        if (!$appointment->feedback_eligible) {
            return redirect()->route('counselee.appointments.index')
                ->withErrors(['error' => 'Feedback becomes available once the session\'s scheduled time has passed.']);
        }

        if ($appointment->feedback) {
            return redirect()->route('counselee.appointments.index')
                ->with('success', 'You have already submitted feedback for this session.');
        }

        return null;
    }
}
