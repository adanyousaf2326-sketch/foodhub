<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderEditRequest extends Model
{
    protected $fillable = [
        'order_id',
        'customer_name',
        'phone',
        'status',
        'message',
        'admin_response',
        'accepted_at',
        'expires_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }

    public function isWithinEditWindow(): bool
    {
        return $this->status === 'accepted'
            && $this->expires_at
            && now()->lt($this->expires_at);
    }
}
