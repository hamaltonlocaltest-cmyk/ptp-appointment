<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CounselType extends Model
{
    protected $table = 'counsel_types';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Auto generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function counselors()
    {
        return $this->belongsToMany(
            Counselor::class,
            'counselor_counsel_type',
            'counsel_type_id',
            'counselor_id'
        )->withTimestamps();
    }

    // Scope for active only
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
