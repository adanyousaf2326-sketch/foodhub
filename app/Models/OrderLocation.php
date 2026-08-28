<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLocation extends Model
{
    protected $fillable = [
        'order_id',
        'latitude',
        'longitude',
        'delivery_lat',
        'delivery_lng',
        'restaurant_lat',
        'restaurant_lng',
        'delivery_status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'delivery_lat' => 'float',
        'delivery_lng' => 'float',
        'restaurant_lat' => 'float',
        'restaurant_lng' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
