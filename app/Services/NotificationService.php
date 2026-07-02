<?php

namespace App\Services;

use App\Mail\AppointmentBooked;
use App\Mail\AppointmentBookedCounselor;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentCompleted;
use App\Mail\AppointmentRescheduled;
use App\Models\Appointment;
use App\Models\AppointmentReschedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function notifyAppointmentBooked(Appointment $appointment): void
    {
        $appointment->loadMissing('counselee', 'counselor', 'counselType');

        $this->safeSend($appointment->counselee->email, new AppointmentBooked($appointment));
        $this->safeSend($appointment->counselor->email, new AppointmentBookedCounselor($appointment));
    }

    public function notifyAppointmentCancelled(Appointment $appointment): void
    {
        $appointment->loadMissing('counselee', 'counselor', 'counselType');

        // Notify whichever party did NOT initiate the cancellation.
        $recipients = match ($appointment->cancelled_by) {
            'counselee' => [$appointment->counselor->email],
            'counselor' => [$appointment->counselee->email],
            default     => [$appointment->counselee->email, $appointment->counselor->email],
        };

        foreach ($recipients as $email) {
            $this->safeSend($email, new AppointmentCancelled($appointment));
        }
    }

    public function notifyAppointmentRescheduled(Appointment $appointment, AppointmentReschedule $reschedule): void
    {
        $appointment->loadMissing('counselee', 'counselor', 'counselType');
        $reschedule->loadMissing('oldCounselor', 'newCounselor');

        $this->safeSend($appointment->counselee->email, new AppointmentRescheduled($appointment, $reschedule));
        $this->safeSend($appointment->counselor->email, new AppointmentRescheduled($appointment, $reschedule));
    }

    public function notifyAppointmentCompleted(Appointment $appointment): void
    {
        $appointment->loadMissing('counselee', 'counselor', 'counselType');

        $this->safeSend($appointment->counselee->email, new AppointmentCompleted($appointment));
    }

    private function safeSend(string $email, $mailable): void
    {
        try {
            Mail::to($email)->send($mailable);
        } catch (\Throwable $e) {
            Log::error('Notification email failed: ' . $e->getMessage());
        }
    }
}
