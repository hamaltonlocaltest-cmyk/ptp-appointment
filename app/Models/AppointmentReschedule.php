<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AppointmentReschedule extends Model
{
    protected $fillable = [
        'appointment_id',
        'old_appointment_date',
        'old_start_time',
        'old_end_time',
        'old_counselor_id',
        'new_appointment_date',
        'new_start_time',
        'new_end_time',
        'new_counselor_id',
        'rescheduled_by',
        'reason',
    ];

    protected $casts = [
        'old_appointment_date' => 'date',
        'new_appointment_date' => 'date',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function oldCounselor()
    {
        return $this->belongsTo(Counselor::class, 'old_counselor_id');
    }

    public function newCounselor()
    {
        return $this->belongsTo(Counselor::class, 'new_counselor_id');
    }

    public function getFormattedOldTimeAttribute(): string
    {
        return Carbon::createFromTimeString($this->old_start_time)->format('g:i A')
             . ' – '
             . Carbon::createFromTimeString($this->old_end_time)->format('g:i A');
    }

    public function getFormattedNewTimeAttribute(): string
    {
        return Carbon::createFromTimeString($this->new_start_time)->format('g:i A')
             . ' – '
             . Carbon::createFromTimeString($this->new_end_time)->format('g:i A');
    }
}
