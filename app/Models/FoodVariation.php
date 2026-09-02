<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodVariation extends Model
{
    protected $fillable = [
        'food_id',
        'name',
        'price',
        'discount_percentage',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function getDiscountedPriceAttribute(): float
    {
        $discount = (float) $this->discount_percentage;
        if ($discount > 0) {
            return round((float) $this->price * (1 - ($discount / 100)), 2);
        }

        // Fallback to food's discount if variant has no specific discount
        if ($this->food && $this->food->hasDiscount()) {
            return round((float) $this->price * (1 - ((float) $this->food->discount_percentage / 100)), 2);
        }

        return (float) $this->price;
    }

    public function hasDiscount(): bool
    {
        return (float) $this->discount_percentage > 0 || ($this->food && $this->food->hasDiscount());
    }
}
