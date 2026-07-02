<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Counselee;

class CounseleeMedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'counselee_medical_histories';

    protected $fillable = [

        'counselee_id',
        'condition',
        'details',

    ];

    public function counselee()
    {
        return $this->belongsTo(Counselee::class);
    }
}

