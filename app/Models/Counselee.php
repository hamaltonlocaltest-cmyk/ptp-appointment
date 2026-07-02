<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CounseleeChild;
use App\Models\CounseleeMedicalHistory;
use App\Models\CounseleeCounsellingArea;

class Counselee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'counselees';

    protected $fillable = [

        // Personal Information
        'title',
        'first_name',
        'last_name',
        'address',
        'telephone1',
        'telephone2',
        'email',
        'age',
        'birthdate',
        'gender',
        'marital_status',

        // Referral
        'referral',

        // Previous Counselling
        'previous_counselling',
        'previous_counselling_details',

        // Login
        'password',

        // Others
        'profile_photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [

        'birthdate' => 'date',
        'email_verified_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(CounseleeChild::class);
    }

    public function medicalHistories()
    {
        return $this->hasMany(CounseleeMedicalHistory::class);
    }

    public function counsellingAreas()
    {
        return $this->hasMany(CounseleeCounsellingArea::class);
    }

    public function counselTypes()
    {
        return $this->belongsToMany(
            \App\Models\CounselType::class,
            'counselee_counsel_type',
            'counselee_id',
            'counsel_type_id'
        )->withTimestamps();
    }



}