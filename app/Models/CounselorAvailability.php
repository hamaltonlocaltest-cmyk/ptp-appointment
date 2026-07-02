<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselorAvailability extends Model
{
    protected $table = 'counselor_availabilities';

    protected $fillable = [
        'counselor_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }
}