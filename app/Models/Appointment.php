<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'counselee_id',
        'counselor_id',
        'counsel_type_id',
        'appointment_date',
        'start_time',
        'end_time',
        'mode',
        'status',
        'notes',
        'counselor_notes',
        'confirmed_at',
        'cancelled_at',
        'cancelled_by',
        'completed_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'confirmed_at'     => 'datetime',
        'cancelled_at'     => 'datetime',
        'completed_at'     => 'datetime',
    ];

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function counselee()
    {
        return $this->belongsTo(Counselee::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function counselType()
    {
        return $this->belongsTo(CounselType::class);
    }

    public function reschedules()
    {
        return $this->hasMany(AppointmentReschedule::class)->latest();
    }

    public function feedback()
    {
        return $this->hasOne(AppointmentFeedback::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // -----------------------------------------------------------------------
    // Accessors
    // -----------------------------------------------------------------------

    public function getFormattedDateAttribute(): string
    {
        return $this->appointment_date->format('l, F j, Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        return Carbon::createFromTimeString($this->start_time)->format('g:i A')
             . ' – '
             . Carbon::createFromTimeString($this->end_time)->format('g:i A');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            default     => 'secondary',
        };
    }

    // True once the session's scheduled end time has actually passed (not just
    // the calendar date) and it hasn't already been completed/cancelled — i.e.
    // safe to mark completed. Accepts 'pending' as well as 'confirmed' so a
    // booking that never got explicitly confirmed doesn't get stuck forever.
    public function getIsCompletableAttribute(): bool
    {
        if (!in_array($this->status, ['pending', 'confirmed'])) {
            return false;
        }

        $endsAt = Carbon::parse($this->appointment_date->toDateString() . ' ' . $this->end_time);

        return $endsAt->lte(now());
    }

    // True once the session's scheduled end time has passed, regardless of
    // whether the counselor has explicitly marked it completed — feedback
    // shouldn't be blocked on that manual step. Cancelled sessions never
    // happened, so they're excluded.
    public function getFeedbackEligibleAttribute(): bool
    {
        if ($this->status === 'cancelled') {
            return false;
        }

        $endsAt = Carbon::parse($this->appointment_date->toDateString() . ' ' . $this->end_time);

        return $endsAt->lte(now());
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->where('appointment_date', '>=', now()->toDateString())
                     ->orderBy('appointment_date')
                     ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->whereIn('status', ['completed', 'cancelled'])
              ->orWhere('appointment_date', '<', now()->toDateString());
        })->orderByDesc('appointment_date')->orderByDesc('start_time');
    }

    // Pending/confirmed appointments whose scheduled end time has passed — eligible to be marked completed
    public function scopeCompletable($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->whereRaw("TIMESTAMP(appointment_date, end_time) <= ?", [now()]);
    }
}
