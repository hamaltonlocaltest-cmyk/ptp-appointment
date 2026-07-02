<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Counselee;

class CounseleeChild extends Model
{
    use HasFactory;

    protected $table = 'counselee_children';

    protected $fillable = [

        'counselee_id',
        'name',
        'gender',
        'age',

    ];

    public function counselee()
    {
        return $this->belongsTo(Counselee::class);
    }
}

