<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';    protected $fillable = [
        'category_id', 'name', 'description', 'price',
        'discount_percentage', 'image', 'is_available', 'prep_time',
    ];



    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(FoodVariation::class)->orderBy('price');
    }

    public function activeVariations()
    {
        return $this->hasMany(FoodVariation::class)->where('is_available', true)->orderBy('price');
    }

    public function hasVariations(): bool
    {
        if ($this->relationLoaded('variations')) {
            return $this->variations->isNotEmpty();
        }
        return $this->variations()->exists();
    }

    public function getMinPriceAttribute(): float
    {
        if ($this->hasVariations()) {
            $variations = $this->relationLoaded('variations') ? $this->variations : $this->variations()->get();
            return (float) $variations->min(fn($v) => $v->discounted_price);
        }
        return $this->discounted_price;
    }

    public function getMaxPriceAttribute(): float
    {
        if ($this->hasVariations()) {
            $variations = $this->relationLoaded('variations') ? $this->variations : $this->variations()->get();
            return (float) $variations->max(fn($v) => $v->discounted_price);
        }
        return $this->discounted_price;
    }

    public function getPriceRangeAttribute(): string
    {
        if ($this->hasVariations()) {
            $variations = $this->relationLoaded('variations') ? $this->variations : $this->variations()->get();
            $min = $variations->min(fn($v) => $v->discounted_price);
            $max = $variations->max(fn($v) => $v->discounted_price);
            if ($min == $max) {
                return 'Rs. ' . number_format($min, 2);
            }
            return 'Rs. ' . number_format($min, 0) . ' - ' . number_format($max, 0);
        }
        return 'Rs. ' . number_format($this->discounted_price, 2);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
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