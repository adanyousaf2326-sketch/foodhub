<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'p256dh_key',
        'auth_key',
        'user_agent',
    ];
}
