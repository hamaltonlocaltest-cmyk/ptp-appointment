<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'counselee_id',
        'donor_name',
        'donor_email',
        'amount',
        'currency',
        'status',
        'instamojo_payment_request_id',
        'payment_reference',
        'gateway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function counselee()
    {
        return $this->belongsTo(Counselee::class);
    }

    public function getDonorDisplayNameAttribute(): string
    {
        if ($this->counselee) {
            return $this->counselee->full_name;
        }

        return $this->donor_name ?: 'Anonymous';
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
