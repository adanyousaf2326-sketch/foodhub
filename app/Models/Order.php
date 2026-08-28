<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
    'customer_name',
    'phone',
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
];

    protected $casts = [
        'total_amount' => 'decimal:2',
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
}