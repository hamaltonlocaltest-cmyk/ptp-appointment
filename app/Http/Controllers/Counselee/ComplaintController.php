<?php

namespace App\Http\Controllers\Counselee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function index()
    {
        $counselee = Auth::guard('counselee')->user();

        $complaints = Complaint::where('counselee_id', $counselee->id)
            ->where('filed_by', 'counselee')
            ->latest()
            ->get();

        return view('counselee.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $counselee = Auth::guard('counselee')->user();

        $appointments = Appointment::where('counselee_id', $counselee->id)
            ->whereIn('status', ['completed', 'confirmed', 'pending'])
            ->with('counselType')
            ->orderByDesc('appointment_date')
            ->get();

        return view('counselee.complaints.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $counselee = Auth::guard('counselee')->user();

        $validated = $request->validate([
            'appointment_id' => 'nullable|integer|exists:appointments,id',
            'subject'        => 'required|string|max:150',
            'description'    => 'required|string|max:2000',
        ]);

        $counselorId = null;

        if (!empty($validated['appointment_id'])) {
            $appointment = Appointment::where('id', $validated['appointment_id'])
                ->where('counselee_id', $counselee->id)
                ->first();

            if (!$appointment) {
                return back()->withErrors(['appointment_id' => 'Invalid appointment selected.'])->withInput();
            }

            $counselorId = $appointment->counselor_id;
        }

        $complaint = Complaint::create([
            'filed_by'       => 'counselee',
            'counselee_id'   => $counselee->id,
            'counselor_id'   => $counselorId,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'subject'        => $validated['subject'],
            'description'    => $validated['description'],
        ]);

        $this->notifications->notifyComplaintReceived($complaint);

        return redirect()->route('counselee.complaints.index')
            ->with('success', 'Your complaint has been submitted. Reference number: ' . $complaint->reference_number);
    }
}
