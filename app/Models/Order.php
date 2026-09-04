<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
    'customer_name',
    'phone',
    'email',
    'address',
    'total_amount',
    'payment_method',
    'status',
    'notes',
    'order_type',
    'table_id',
    'paid_amount',
    'change_amount',
    'paid_at',
    'has_edited',
    'delivery_charges',
    'delivery_distance_km',
    'delivery_time_min',
    'customer_lat',
    'customer_lng',
    'rider_id',
    'picked_up_at',
    'returned_at',
];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_charges' => 'decimal:2',
        'picked_up_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function editRequests()
    {
        return $this->hasMany(OrderEditRequest::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function location()
    {
        return $this->hasOne(OrderLocation::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }

    public function canBeCancelled(): bool
    {
        // Can't cancel if already completed/delivered/cancelled
        if (in_array($this->status, ['Completed', 'Delivered', 'Cancelled'])) {
            return false;
        }
        // Can't cancel if rider has picked up the order
        if ($this->picked_up_at) {
            return false;
        }
        return true;
    }
}