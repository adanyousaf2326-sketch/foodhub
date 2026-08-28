<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'order_id',
        'stars',
        'review',
        'customer_name',
    ];

    protected $casts = [
        'stars' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
