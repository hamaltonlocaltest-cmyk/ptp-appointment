<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentReschedule;
use App\Models\Counselor;
use App\Services\NotificationService;
use App\Services\SlotAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function __construct(
        private SlotAvailabilityService $slots,
        private NotificationService $notifications,
    ) {}

    // -----------------------------------------------------------------------
    // Index — counselor's appointments list
    // -----------------------------------------------------------------------
    public function index()
    {
        $counselor = Auth::guard('counselor')->user();

        $upcoming = Appointment::with(['counselee', 'counselType', 'reschedules'])
            ->where('counselor_id', $counselor->id)
            ->upcoming()
            ->get();

        $past = Appointment::with(['counselee', 'counselType', 'reschedules'])
            ->where('counselor_id', $counselor->id)
            ->past()
            ->take(20)
            ->get();

        return view('counselor.appointments.index', compact('upcoming', 'past'));
    }

    // -----------------------------------------------------------------------
    // Show — full appointment detail, including editable counselor notes
    // -----------------------------------------------------------------------
    public function show(Appointment $appointment)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        $appointment->load(['counselee', 'counselType', 'reschedules', 'feedback', 'complaints']);

        return view('counselor.appointments.show', compact('appointment'));
    }

    // -----------------------------------------------------------------------
    // Update the counselor's post-session notes for an appointment
    // -----------------------------------------------------------------------
    public function updateNotes(Request $request, Appointment $appointment)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'counselor_notes' => 'nullable|string|max:2000',
        ]);

        $appointment->update(['counselor_notes' => $validated['counselor_notes'] ?? null]);

        return back()->with('success', 'Session notes saved.');
    }

    // -----------------------------------------------------------------------
    // Cancel
    // -----------------------------------------------------------------------
    public function cancel(Appointment $appointment)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment cannot be cancelled.']);
        }

        $appointment->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'counselor',
        ]);

        $this->notifications->notifyAppointmentCancelled($appointment);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    // -----------------------------------------------------------------------
    // Mark as completed
    // -----------------------------------------------------------------------
    public function complete(Appointment $appointment)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        if (!$appointment->is_completable) {
            return back()->withErrors(['error' => 'This appointment can only be marked completed once its scheduled time has passed.']);
        }

        $appointment->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        $this->notifications->notifyAppointmentCompleted($appointment);

        return back()->with('success', 'Appointment marked as completed.');
    }

    // -----------------------------------------------------------------------
    // Reschedule — Step 1: show date/time picker for an existing appointment
    // -----------------------------------------------------------------------
    public function editReschedule(Appointment $appointment)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment can no longer be rescheduled.']);
        }

        $appointment->load('counselee', 'counselType');

        return view('counselor.appointments.reschedule', compact('appointment'));
    }

    // -----------------------------------------------------------------------
    // Reschedule — AJAX: get available dates for this appointment's counsel type
    // -----------------------------------------------------------------------
    public function getRescheduleDates(Appointment $appointment)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        return response()->json(['dates' => $this->slots->availableDates($appointment->counsel_type_id)]);
    }

    // -----------------------------------------------------------------------
    // Reschedule — AJAX: get available slots for a new date (any matching counselor)
    // -----------------------------------------------------------------------
    public function getRescheduleSlots(Appointment $appointment, Request $request)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        $request->validate(['date' => 'required|date|after:today']);

        $slots = $this->slots->availableSlots(
            $appointment->counsel_type_id,
            $request->date,
            $appointment->counselee_id,
            $appointment->id
        );

        return response()->json(['slots' => $slots]);
    }

    // -----------------------------------------------------------------------
    // Reschedule — submit new date/time (and possibly a new counselor)
    // -----------------------------------------------------------------------
    public function reschedule(Appointment $appointment, Request $request)
    {
        $counselor = Auth::guard('counselor')->user();

        if ($appointment->counselor_id !== $counselor->id) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment can no longer be rescheduled.']);
        }

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'counselor_id'     => 'required|integer|exists:counselors,id',
            'reason'           => 'nullable|string|max:500',
        ]);

        $newCounselor = Counselor::findOrFail($validated['counselor_id']);
        $newDate      = Carbon::parse($validated['appointment_date']);

        if (!$this->slots->counselorIsAvailable($newCounselor, $newDate, $validated['start_time'], $validated['end_time'])) {
            return back()->withErrors(['slot' => 'That counselor is no longer available at that date/time. Please choose another slot.']);
        }

        if ($this->slots->counselorHasConflict($validated['counselor_id'], $validated['appointment_date'], $validated['start_time'], $appointment->id)) {
            return back()->withErrors(['slot' => 'That slot was just taken. Please choose another.']);
        }

        if ($this->slots->counseleeHasConflict($appointment->counselee_id, $validated['appointment_date'], $validated['start_time'], $appointment->id)) {
            return back()->withErrors(['slot' => 'The counsellee already has another appointment at that date and time.']);
        }

        DB::beginTransaction();
        try {
            $log = AppointmentReschedule::create([
                'appointment_id'        => $appointment->id,
                'old_appointment_date'  => $appointment->appointment_date,
                'old_start_time'        => $appointment->start_time,
                'old_end_time'          => $appointment->end_time,
                'old_counselor_id'      => $appointment->counselor_id,
                'new_appointment_date'  => $validated['appointment_date'],
                'new_start_time'        => $validated['start_time'],
                'new_end_time'          => $validated['end_time'],
                'new_counselor_id'      => $validated['counselor_id'],
                'rescheduled_by'        => 'counselor',
                'reason'                => $validated['reason'] ?? null,
            ]);

            $appointment->update([
                'counselor_id'     => $validated['counselor_id'],
                'appointment_date' => $validated['appointment_date'],
                'start_time'       => $validated['start_time'],
                'end_time'         => $validated['end_time'],
                'mode'             => $newCounselor->mode,
                'status'           => 'confirmed',
                'confirmed_at'     => now(),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Appointment reschedule failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Reschedule failed. Please try again.']);
        }

        $this->notifications->notifyAppointmentRescheduled($appointment, $log);

        return redirect()->route('counselor.appointments.index')
            ->with('success', 'Appointment rescheduled successfully.');
    }
}
