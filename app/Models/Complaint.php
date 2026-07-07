<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'reference_number',
        'filed_by',
        'counselee_id',
        'counselor_id',
        'appointment_id',
        'subject',
        'description',
        'status',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // Auto-generate a unique reference number, mirroring CounselType's slug-generation pattern
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->reference_number)) {
                $model->reference_number = static::generateReferenceNumber();
            }
        });
    }

    public static function generateReferenceNumber(): string
    {
        $year = now()->format('Y');

        do {
            $number = 'CMP-' . $year . '-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (static::where('reference_number', $number)->exists());

        return $number;
    }

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

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopeOpenStatus($query)
    {
        return $query->whereIn('status', ['open', 'in_review']);
    }
}
