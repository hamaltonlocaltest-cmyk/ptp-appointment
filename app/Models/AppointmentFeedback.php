<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentFeedback extends Model
{
    // 'feedback' is uncountable, so Eloquent's default pluralization would
    // guess 'appointment_feedback' — the migration created 'appointment_feedbacks'.
    protected $table = 'appointment_feedbacks';

    protected $fillable = [
        'appointment_id',
        'counselee_id',
        'counselor_id',
        'rating',
        'comments',
        'submitted_at',
    ];

    protected $casts = [
        'rating'       => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function counselee()
    {
        return $this->belongsTo(Counselee::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }
}
