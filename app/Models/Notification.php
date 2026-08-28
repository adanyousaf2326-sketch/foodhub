<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'title',
        'message',
        'email',
        'is_read',
        'email_sent',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
