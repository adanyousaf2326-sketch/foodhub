<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodSize extends Model
{
    protected $fillable = [
        'food_id',
        'name',
        'price',
        'discount_percentage',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function getDiscountedPriceAttribute(): float
    {
        return round(
            (float) $this->price * (1 - ((float) $this->discount_percentage / 100)),
            2
        );
    }

    public function hasDiscount(): bool
    {
        return (float) $this->discount_percentage > 0;
    }
}
