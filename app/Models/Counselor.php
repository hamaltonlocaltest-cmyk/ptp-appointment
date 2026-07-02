<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Counselor extends Authenticatable
{
    use Notifiable;

    protected $table = 'counselors';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'specialization',
        'experience_years',
        'mode',
        'languages',
        'training_level',
        'password',
        'profile_photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'experience_years'  => 'integer',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function counselTypes()
    {
        return $this->belongsToMany(
            CounselType::class,
            'counselor_counsel_type',
            'counselor_id',
            'counsel_type_id'
        )->withTimestamps();
    }

    public function availabilities()
    {
        return $this->hasMany(CounselorAvailability::class);
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}