<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Counselee;

class CounseleeCounsellingArea extends Model
{
    use HasFactory;

    protected $table = 'counselee_counselling_areas';

    protected $fillable = [

        'counselee_id',
        'area',

    ];

    public function counselee()
    {
        return $this->belongsTo(Counselee::class);
    }
}

