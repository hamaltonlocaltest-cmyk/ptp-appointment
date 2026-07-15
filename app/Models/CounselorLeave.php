<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselorLeave extends Model
{
    protected $table = 'counselor_leaves';

    protected $fillable = [
        'counselor_id',
        'start_date',
        'end_date',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    // Leave records that cover the given date (inclusive).
    public function scopeCovering($query, $date)
    {
        return $query->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date);
    }

    // Leave records that haven't fully elapsed yet (still relevant to show/cancel).
    public function scopeUpcoming($query)
    {
        return $query->where('end_date', '>=', now()->toDateString());
    }
}
