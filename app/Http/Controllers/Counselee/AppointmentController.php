<?php

namespace App\Http\Controllers\Counselee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentReschedule;
use App\Models\Counselor;
use App\Models\CounselType;
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
    // Step 1 — Show booking page (counsel type selection)
    // -----------------------------------------------------------------------
    public function create()
    {
        $counselee = Auth::guard('counselee')->user();

        // Load the counselee's registered counsel types
        $counselee->load('counselTypes');
        $myCounselTypes = $counselee->counselTypes;

        // From those types, only show ones that have at least one active counselor
        $availableTypes = $myCounselTypes->filter(function ($type) {
            return Counselor::active()
                ->whereHas('counselTypes', fn($q) => $q->where('counsel_types.id', $type->id))
                ->exists();
        })->values();

        return view('counselee.appointments.create', compact('availableTypes'));
    }

    // -----------------------------------------------------------------------
    // Step 2 — AJAX: get available dates for a given counsel type
    // Returns dates (next 30 days) where at least one matching counselor
    // has availability and no fully-booked day.
    // -----------------------------------------------------------------------
    public function getAvailableDates(Request $request)
    {
        $request->validate(['counsel_type_id' => 'required|integer|exists:counsel_types,id']);

        $counselee = Auth::guard('counselee')->user();
        $typeId    = (int) $request->counsel_type_id;

        $dates = $this->slots->availableDates($typeId, $counselee->city_id);

        $response = ['dates' => $dates];

        if (empty($dates) && $this->cityIsTheBlocker($typeId, $counselee->city_id)) {
            $response['message'] = 'No counselors are currently available in your city for in-person sessions. Please check back later, or look for a counsellor offering online sessions.';
        }

        return response()->json($response);
    }

    // -----------------------------------------------------------------------
    // Step 3 — AJAX: get available time slots for a type + date
    // Auto-selects the best-fit counselor per slot (least booked first).
    // -----------------------------------------------------------------------
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'counsel_type_id' => 'required|integer|exists:counsel_types,id',
            'date'            => 'required|date|after:today',
        ]);

        $counselee = Auth::guard('counselee')->user();
        $typeId    = (int) $request->counsel_type_id;

        $slots = $this->slots->availableSlots($typeId, $request->date, $counselee->id, null, $counselee->city_id);

        $response = ['slots' => $slots];

        if (empty($slots) && $this->cityIsTheBlocker($typeId, $counselee->city_id)) {
            $response['message'] = 'No counselors are available in your city for in-person sessions. Try a different date, or look for online sessions instead.';
        }

        return response()->json($response);
    }

    // True when counselors exist for this type in general, but none of them
    // can be matched once we restrict to the counselee's city (i.e. the city,
    // not a fully-booked calendar, is why nothing came back).
    private function cityIsTheBlocker(int $counselTypeId, ?int $cityId): bool
    {
        if (!$cityId) {
            return false;
        }

        return $this->slots->hasAnyCounselorForType($counselTypeId)
            && $this->slots->matchingCounselors($counselTypeId, $cityId)->isEmpty();
    }

    // -----------------------------------------------------------------------
    // Step 4 — Show confirmation preview before final submit
    // -----------------------------------------------------------------------
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'counsel_type_id' => 'required|integer|exists:counsel_types,id',
            'appointment_date'=> 'required|date|after:today',
            'start_time'      => 'required|date_format:H:i',
            'end_time'        => 'required|date_format:H:i|after:start_time',
            'counselor_id'    => 'required|integer|exists:counselors,id',
            'notes'           => 'nullable|string|max:500',
        ]);

        $counselType = CounselType::findOrFail($validated['counsel_type_id']);
        $counselor   = Counselor::findOrFail($validated['counselor_id']);
        $date        = Carbon::parse($validated['appointment_date']);
        $counselee   = Auth::guard('counselee')->user();

        if (!$this->slots->counselorIsAvailable($counselor, $date, $validated['start_time'], $validated['end_time'])) {
            return redirect()->route('counselee.appointments.create')
                ->withErrors(['slot' => 'This counselor is no longer available at that date/time. Please choose another slot.']);
        }

        if ($this->slots->counselorHasConflict($validated['counselor_id'], $validated['appointment_date'], $validated['start_time'])) {
            return back()->withErrors(['slot' => 'This time slot was just taken. Please choose another.']);
        }

        if ($this->slots->counseleeHasConflict($counselee->id, $validated['appointment_date'], $validated['start_time'])) {
            return redirect()->route('counselee.appointments.create')
                ->withErrors(['slot' => 'You already have an appointment at that date and time.']);
        }

        return view('counselee.appointments.preview', compact(
            'validated', 'counselType', 'counselor', 'date'
        ));
    }

    // -----------------------------------------------------------------------
    // Store — final submit after preview confirmation
    // -----------------------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'counsel_type_id' => 'required|integer|exists:counsel_types,id',
            'appointment_date'=> 'required|date|after:today',
            'start_time'      => 'required|date_format:H:i',
            'end_time'        => 'required|date_format:H:i|after:start_time',
            'counselor_id'    => 'required|integer|exists:counselors,id',
            'notes'           => 'nullable|string|max:500',
        ]);

        $counselee = Auth::guard('counselee')->user();
        $counselor = Counselor::findOrFail($validated['counselor_id']);
        $date      = Carbon::parse($validated['appointment_date']);

        if (!$this->slots->counselorIsAvailable($counselor, $date, $validated['start_time'], $validated['end_time'])) {
            return redirect()->route('counselee.appointments.create')
                ->withErrors(['slot' => 'This counselor is no longer available at that date/time. Please choose another slot.']);
        }

        if ($this->slots->counselorHasConflict($validated['counselor_id'], $validated['appointment_date'], $validated['start_time'])) {
            return redirect()->route('counselee.appointments.create')
                ->withErrors(['slot' => 'That slot was just taken by someone else. Please choose another time.']);
        }

        if ($this->slots->counseleeHasConflict($counselee->id, $validated['appointment_date'], $validated['start_time'])) {
            return redirect()->route('counselee.appointments.create')
                ->withErrors(['slot' => 'You already have an appointment at that date and time.']);
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::create([
                'counselee_id'    => $counselee->id,
                'counselor_id'    => $validated['counselor_id'],
                'counsel_type_id' => $validated['counsel_type_id'],
                'appointment_date'=> $validated['appointment_date'],
                'start_time'      => $validated['start_time'],
                'end_time'        => $validated['end_time'],
                'mode'            => $counselor->mode,
                'status'          => 'confirmed',
                'confirmed_at'    => now(),
                'notes'           => $validated['notes'] ?? null,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Appointment booking failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Booking failed. Please try again.']);
        }

        $this->notifications->notifyAppointmentBooked($appointment);

        return redirect()->route('counselee.appointments.index')
            ->with('success', 'Your appointment has been booked! You will receive a confirmation email shortly.');
    }

    // -----------------------------------------------------------------------
    // Index — counselee's appointments list
    // -----------------------------------------------------------------------
    public function index()
    {
        $counselee = Auth::guard('counselee')->user();

        $upcoming = Appointment::with(['counselor', 'counselType', 'reschedules'])
            ->where('counselee_id', $counselee->id)
            ->upcoming()
            ->get();

        $past = Appointment::with(['counselor', 'counselType', 'reschedules', 'feedback'])
            ->where('counselee_id', $counselee->id)
            ->past()
            ->take(20)
            ->get();

        return view('counselee.appointments.index', compact('upcoming', 'past'));
    }

    // -----------------------------------------------------------------------
    // Show — full appointment detail, including feedback status
    // -----------------------------------------------------------------------
    public function show(Appointment $appointment)
    {
        $counselee = Auth::guard('counselee')->user();

        if ($appointment->counselee_id !== $counselee->id) {
            abort(403);
        }

        $appointment->load(['counselor', 'counselType', 'reschedules', 'feedback', 'complaints']);

        return view('counselee.appointments.show', compact('appointment'));
    }

    // -----------------------------------------------------------------------
    // Cancel
    // -----------------------------------------------------------------------
    public function cancel(Appointment $appointment)
    {
        $counselee = Auth::guard('counselee')->user();

        if ($appointment->counselee_id !== $counselee->id) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment cannot be cancelled.']);
        }

        $appointment->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'counselee',
        ]);

        $this->notifications->notifyAppointmentCancelled($appointment);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    // -----------------------------------------------------------------------
    // Reschedule — Step 1: show date/time picker for an existing appointment
    // -----------------------------------------------------------------------
    public function editReschedule(Appointment $appointment)
    {
        $counselee = Auth::guard('counselee')->user();

        if ($appointment->counselee_id !== $counselee->id) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment can no longer be rescheduled.']);
        }

        $appointment->load('counselor', 'counselType');

        return view('counselee.appointments.reschedule', compact('appointment'));
    }

    // -----------------------------------------------------------------------
    // Reschedule — AJAX: get available slots for a new date (any matching counselor)
    // -----------------------------------------------------------------------
    public function getRescheduleSlots(Appointment $appointment, Request $request)
    {
        $counselee = Auth::guard('counselee')->user();

        if ($appointment->counselee_id !== $counselee->id) {
            abort(403);
        }

        $request->validate(['date' => 'required|date|after:today']);

        $slots = $this->slots->availableSlots(
            $appointment->counsel_type_id,
            $request->date,
            $counselee->id,
            $appointment->id,
            $counselee->city_id
        );

        $response = ['slots' => $slots];

        if (empty($slots) && $this->cityIsTheBlocker($appointment->counsel_type_id, $counselee->city_id)) {
            $response['message'] = 'No counselors are available in your city for in-person sessions. Try a different date, or look for online sessions instead.';
        }

        return response()->json($response);
    }

    // -----------------------------------------------------------------------
    // Reschedule — submit new date/time (and possibly a new counselor)
    // -----------------------------------------------------------------------
    public function reschedule(Appointment $appointment, Request $request)
    {
        $counselee = Auth::guard('counselee')->user();

        if ($appointment->counselee_id !== $counselee->id) {
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

        if ($this->slots->counseleeHasConflict($counselee->id, $validated['appointment_date'], $validated['start_time'], $appointment->id)) {
            return back()->withErrors(['slot' => 'You already have another appointment at that date and time.']);
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
                'rescheduled_by'        => 'counselee',
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

        return redirect()->route('counselee.appointments.index')
            ->with('success', 'Your appointment has been rescheduled successfully.');
    }
}
