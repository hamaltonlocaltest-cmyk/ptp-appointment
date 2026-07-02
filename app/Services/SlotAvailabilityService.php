<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Counselor;
use App\Models\CounselorAvailability;
use Carbon\Carbon;

// Shared slot-matching logic used by both the original booking wizard
// and the reschedule flow (counselee- and counselor-initiated).
class SlotAvailabilityService
{
    public function matchingCounselors(int $counselTypeId)
    {
        return Counselor::where('status', 'active')
            ->whereHas('counselTypes', fn($q) => $q->where('counsel_types.id', $counselTypeId))
            ->whereHas('availabilities')
            ->get();
    }

    public function availableDates(int $counselTypeId, int $limit = 30, int $windowDays = 60): array
    {
        $counselors = $this->matchingCounselors($counselTypeId);

        if ($counselors->isEmpty()) {
            return [];
        }

        $dates = [];
        $today = Carbon::today();

        for ($i = 1; $i <= $windowDays; $i++) {
            $date    = $today->copy()->addDays($i);
            $dayName = $date->format('l');

            $hasAvailability = CounselorAvailability::whereIn('counselor_id', $counselors->pluck('id'))
                ->where('day', $dayName)
                ->exists();

            if ($hasAvailability) {
                $dates[] = $date->toDateString();
            }

            if (count($dates) >= $limit) break;
        }

        return $dates;
    }

    // $excludeAppointmentId lets the reschedule flow re-check availability
    // without the appointment-being-rescheduled blocking its own old slot.
    public function availableSlots(int $counselTypeId, string $date, int $counseleeId, ?int $excludeAppointmentId = null): array
    {
        $counselors = $this->matchingCounselors($counselTypeId);

        if ($counselors->isEmpty()) {
            return [];
        }

        $dayName = Carbon::parse($date)->format('l');

        $bookingsQuery = Appointment::whereIn('counselor_id', $counselors->pluck('id'))
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled']);
        if ($excludeAppointmentId) {
            $bookingsQuery->where('id', '!=', $excludeAppointmentId);
        }
        $existingBookings = $bookingsQuery->get()->groupBy('counselor_id');

        $counseleeBookedQuery = Appointment::where('counselee_id', $counseleeId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled']);
        if ($excludeAppointmentId) {
            $counseleeBookedQuery->where('id', '!=', $excludeAppointmentId);
        }
        $counseleeBookedTimes = $counseleeBookedQuery->pluck('start_time')->toArray();

        $slots = [];

        foreach ($counselors as $counselor) {
            $daySlots = CounselorAvailability::where('counselor_id', $counselor->id)
                ->where('day', $dayName)
                ->get();

            $bookedTimes = ($existingBookings[$counselor->id] ?? collect())
                ->pluck('start_time')
                ->toArray();

            foreach ($daySlots as $avail) {
                $current = Carbon::createFromTimeString($avail->start_time);
                $end     = Carbon::createFromTimeString($avail->end_time);

                while ($current->copy()->addHour()->lte($end)) {
                    $slotStart = $current->format('H:i');
                    $slotEnd   = $current->copy()->addHour()->format('H:i');

                    $counselorTaken = in_array($slotStart . ':00', $bookedTimes) || in_array($slotStart, $bookedTimes);
                    $counseleeTaken = in_array($slotStart . ':00', $counseleeBookedTimes) || in_array($slotStart, $counseleeBookedTimes);

                    if (!$counselorTaken && !$counseleeTaken) {
                        $key = $slotStart . '|' . $slotEnd;

                        if (!isset($slots[$key])) {
                            $slots[$key] = [
                                'start_time'     => $slotStart,
                                'end_time'       => $slotEnd,
                                'display'        => Carbon::createFromTimeString($slotStart)->format('g:i A')
                                                   . ' – '
                                                   . Carbon::createFromTimeString($slotEnd)->format('g:i A'),
                                'counselor_id'   => $counselor->id,
                                'counselor_name' => $counselor->full_name,
                                'mode'           => $counselor->mode,
                            ];
                        }
                    }

                    $current->addHour();
                }
            }
        }

        usort($slots, fn($a, $b) => strcmp($a['start_time'], $b['start_time']));

        return array_values($slots);
    }

    public function counselorIsAvailable(Counselor $counselor, Carbon $date, string $startTime, string $endTime): bool
    {
        if ($counselor->status !== 'active') {
            return false;
        }

        $dayName = $date->format('l');
        $start   = Carbon::createFromTimeString($startTime);
        $end     = Carbon::createFromTimeString($endTime);

        return CounselorAvailability::where('counselor_id', $counselor->id)
            ->where('day', $dayName)
            ->get()
            ->contains(function ($avail) use ($start, $end) {
                return Carbon::createFromTimeString($avail->start_time)->lte($start)
                    && Carbon::createFromTimeString($avail->end_time)->gte($end);
            });
    }

    public function counselorHasConflict(int $counselorId, string $date, string $startTime, ?int $excludeAppointmentId = null): bool
    {
        $query = Appointment::where('counselor_id', $counselorId)
            ->where('appointment_date', $date)
            ->where('start_time', $startTime . ':00')
            ->whereNotIn('status', ['cancelled']);

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    public function counseleeHasConflict(int $counseleeId, string $date, string $startTime, ?int $excludeAppointmentId = null): bool
    {
        $query = Appointment::where('counselee_id', $counseleeId)
            ->where('appointment_date', $date)
            ->where('start_time', $startTime . ':00')
            ->whereNotIn('status', ['cancelled']);

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }
}
