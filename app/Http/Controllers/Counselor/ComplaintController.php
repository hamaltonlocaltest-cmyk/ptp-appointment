<?php

namespace App\Http\Controllers\Counselor;

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

    // Shows complaints this counselor has filed, plus (read-only) complaints
    // counselees have filed about sessions with this counselor.
    public function index()
    {
        $counselor = Auth::guard('counselor')->user();

        $filed = Complaint::where('counselor_id', $counselor->id)
            ->where('filed_by', 'counselor')
            ->latest()
            ->get();

        $against = Complaint::with('counselee')
            ->where('counselor_id', $counselor->id)
            ->where('filed_by', 'counselee')
            ->latest()
            ->get();

        return view('counselor.complaints.index', compact('filed', 'against'));
    }

    public function create()
    {
        $counselor = Auth::guard('counselor')->user();

        $appointments = Appointment::where('counselor_id', $counselor->id)
            ->whereIn('status', ['completed', 'confirmed', 'pending'])
            ->with('counselee', 'counselType')
            ->orderByDesc('appointment_date')
            ->get();

        return view('counselor.complaints.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $counselor = Auth::guard('counselor')->user();

        $validated = $request->validate([
            'appointment_id' => 'nullable|integer|exists:appointments,id',
            'subject'        => 'required|string|max:150',
            'description'    => 'required|string|max:2000',
        ]);

        $counseleeId = null;

        if (!empty($validated['appointment_id'])) {
            $appointment = Appointment::where('id', $validated['appointment_id'])
                ->where('counselor_id', $counselor->id)
                ->first();

            if (!$appointment) {
                return back()->withErrors(['appointment_id' => 'Invalid appointment selected.'])->withInput();
            }

            $counseleeId = $appointment->counselee_id;
        }

        $complaint = Complaint::create([
            'filed_by'       => 'counselor',
            'counselor_id'   => $counselor->id,
            'counselee_id'   => $counseleeId,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'subject'        => $validated['subject'],
            'description'    => $validated['description'],
        ]);

        $this->notifications->notifyComplaintReceived($complaint);

        return redirect()->route('counselor.complaints.index')
            ->with('success', 'Your complaint has been submitted. Reference number: ' . $complaint->reference_number);
    }
}
