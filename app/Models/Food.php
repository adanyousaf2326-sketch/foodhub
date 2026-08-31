<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'discount_percentage',
        'image',
        'is_available',
    ];

    protected $with = ['foodSizes'];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function foodSizes()
    {
        return $this->hasMany(FoodSize::class);
    }

    public function hasSizes(): bool
    {
        return $this->foodSizes()->count() > 0;
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