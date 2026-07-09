<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentReschedule;
use App\Models\Counselee;
use App\Models\Counselor;
use App\Models\CounselType;
use App\Services\NotificationService;
use App\Services\SlotAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function __construct(
        private SlotAvailabilityService $slots,
        private NotificationService $notifications,
    ) {}

   
    public function index(Request $request)
    {
        $query = Appointment::with(['counselee', 'counselor', 'counselType'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('counselee', fn($cq) => $cq->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"))
                  ->orWhereHas('counselor', fn($cq) => $cq->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        $appointments = $query->get();

        $counts = [
            'total'     => Appointment::count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'counts'));
    }

    
    public function show(Appointment $appointment)
    {
        $appointment->load(['counselee', 'counselor', 'counselType', 'reschedules.oldCounselor', 'reschedules.newCounselor']);

        return view('admin.appointments.show', compact('appointment'));
    }

    // -----------------------------------------------------------------------
    // Create — book an appointment on behalf of a counselee
    // -----------------------------------------------------------------------
    public function create(Request $request)
    {
        $counselees   = Counselee::where('status', 'active')->orderBy('first_name')->get();
        $counselTypes = CounselType::active()->ordered()->get();
        $selectedCounseleeId = $request->integer('counselee_id') ?: null;

        return view('admin.appointments.create', compact('counselees', 'counselTypes', 'selectedCounseleeId'));
    }

    // -----------------------------------------------------------------------
    // AJAX: available dates for a counsel type
    // -----------------------------------------------------------------------
    public function getAvailableDates(Request $request)
    {
        $request->validate([
            'counsel_type_id' => 'required|integer|exists:counsel_types,id',
            'counselee_id'    => 'nullable|integer|exists:counselees,id',
        ]);

        $typeId = (int) $request->counsel_type_id;
        $cityId = $request->filled('counselee_id')
            ? Counselee::find($request->counselee_id)?->city_id
            : null;

        $dates = $this->slots->availableDates($typeId, $cityId);

        $response = ['dates' => $dates];

        if (empty($dates) && $this->cityIsTheBlocker($typeId, $cityId)) {
            $response['message'] = 'No counselors are available in this counsellee\'s city for in-person sessions. Try a different counselling area, or look for online sessions.';
        }

        return response()->json($response);
    }

    // -----------------------------------------------------------------------
    // AJAX: available slots for a counsel type + date, for a specific counselee
    // -----------------------------------------------------------------------
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'counsel_type_id' => 'required|integer|exists:counsel_types,id',
            'date'            => 'required|date|after:today',
            'counselee_id'    => 'required|integer|exists:counselees,id',
        ]);

        $typeId    = (int) $request->counsel_type_id;
        $counselee = Counselee::find($request->counselee_id);

        $slots = $this->slots->availableSlots(
            $typeId,
            $request->date,
            (int) $request->counselee_id,
            null,
            $counselee?->city_id
        );

        $response = ['slots' => $slots];

        if (empty($slots) && $this->cityIsTheBlocker($typeId, $counselee?->city_id)) {
            $response['message'] = 'No counselors are available in this counsellee\'s city for in-person sessions. Try a different date, or look for online sessions.';
        }

        return response()->json($response);
    }

    // True when counselors exist for this type in general, but none of them
    // can be matched once we restrict to the given city.
    private function cityIsTheBlocker(int $counselTypeId, ?int $cityId): bool
    {
        if (!$cityId) {
            return false;
        }

        return $this->slots->hasAnyCounselorForType($counselTypeId)
            && $this->slots->matchingCounselors($counselTypeId, $cityId)->isEmpty();
    }

    // -----------------------------------------------------------------------
    // Store — book on behalf of a counselee
    // -----------------------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'counselee_id'     => 'required|integer|exists:counselees,id',
            'counsel_type_id'  => 'required|integer|exists:counsel_types,id',
            'appointment_date' => 'required|date|after:today',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'counselor_id'     => 'required|integer|exists:counselors,id',
            'notes'            => 'nullable|string|max:500',
        ]);

        $counselor = Counselor::findOrFail($validated['counselor_id']);
        $date      = Carbon::parse($validated['appointment_date']);

        if (!$this->slots->counselorIsAvailable($counselor, $date, $validated['start_time'], $validated['end_time'])) {
            return back()->withInput()->withErrors(['slot' => 'That counselor is no longer available at that date/time. Please choose another slot.']);
        }

        if ($this->slots->counselorHasConflict($validated['counselor_id'], $validated['appointment_date'], $validated['start_time'])) {
            return back()->withInput()->withErrors(['slot' => 'That slot was just taken. Please choose another.']);
        }

        if ($this->slots->counseleeHasConflict($validated['counselee_id'], $validated['appointment_date'], $validated['start_time'])) {
            return back()->withInput()->withErrors(['slot' => 'This counsellee already has another appointment at that date and time.']);
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::create([
                'counselee_id'     => $validated['counselee_id'],
                'counselor_id'     => $validated['counselor_id'],
                'counsel_type_id'  => $validated['counsel_type_id'],
                'appointment_date' => $validated['appointment_date'],
                'start_time'       => $validated['start_time'],
                'end_time'         => $validated['end_time'],
                'mode'             => $counselor->mode,
                'status'           => 'confirmed',
                'confirmed_at'     => now(),
                'notes'            => $validated['notes'] ?? null,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin appointment booking failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Booking failed. Please try again.']);
        }

        $this->notifications->notifyAppointmentBooked($appointment);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully on behalf of the counsellee.');
    }

    // -----------------------------------------------------------------------
    // Cancel
    // -----------------------------------------------------------------------
    public function cancel(Appointment $appointment)
    {
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment cannot be cancelled.']);
        }

        $appointment->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'admin',
        ]);

        $this->notifications->notifyAppointmentCancelled($appointment);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    // -----------------------------------------------------------------------
    // Reschedule — Step 1: show date/time picker for an existing appointment
    // -----------------------------------------------------------------------
    public function editReschedule(Appointment $appointment)
    {
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment can no longer be rescheduled.']);
        }

        $appointment->load('counselee', 'counselor', 'counselType');

        return view('admin.appointments.reschedule', compact('appointment'));
    }

    // -----------------------------------------------------------------------
    // Reschedule — AJAX: available dates for this appointment's counsel type
    // -----------------------------------------------------------------------
    public function getRescheduleDates(Appointment $appointment)
    {
        $cityId = $appointment->counselee->city_id;
        $dates  = $this->slots->availableDates($appointment->counsel_type_id, $cityId);

        $response = ['dates' => $dates];

        if (empty($dates) && $this->cityIsTheBlocker($appointment->counsel_type_id, $cityId)) {
            $response['message'] = 'No counselors are available in this counsellee\'s city for in-person sessions. Try a different counselling area, or look for online sessions.';
        }

        return response()->json($response);
    }

    // -----------------------------------------------------------------------
    // Reschedule — AJAX: available slots for a new date (any matching counselor)
    // -----------------------------------------------------------------------
    public function getRescheduleSlots(Appointment $appointment, Request $request)
    {
        $request->validate(['date' => 'required|date|after:today']);

        $cityId = $appointment->counselee->city_id;

        $slots = $this->slots->availableSlots(
            $appointment->counsel_type_id,
            $request->date,
            $appointment->counselee_id,
            $appointment->id,
            $cityId
        );

        $response = ['slots' => $slots];

        if (empty($slots) && $this->cityIsTheBlocker($appointment->counsel_type_id, $cityId)) {
            $response['message'] = 'No counselors are available in this counsellee\'s city for in-person sessions. Try a different date, or look for online sessions.';
        }

        return response()->json($response);
    }

    // -----------------------------------------------------------------------
    // Reschedule — submit new date/time (and possibly a new counselor)
    // -----------------------------------------------------------------------
    public function reschedule(Appointment $appointment, Request $request)
    {
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
                'rescheduled_by'        => 'admin',
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
            Log::error('Admin appointment reschedule failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Reschedule failed. Please try again.']);
        }

        $this->notifications->notifyAppointmentRescheduled($appointment, $log);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Appointment rescheduled successfully.');
    }
}
