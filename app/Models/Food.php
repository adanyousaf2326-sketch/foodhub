<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';    protected $fillable = [
        'category_id', 'name', 'description', 'price',
        'discount_percentage', 'image', 'is_available', 'prep_time',
        'stock_quantity', 'is_in_stock', 'low_stock_threshold',
        'available_at',
    ];



    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_available' => 'boolean',
        'available_at' => 'datetime',
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

    /**
     * Check if food is currently orderable (in stock + available_at passed)
     */
    public function isOrderable(): bool
    {
        if (!$this->is_in_stock) {
            // Check if available_at is set and in the future
            if ($this->available_at && $this->available_at->isFuture()) {
                return false;
            }
            // If available_at is past or null, item is disabled permanently until kitchen enables
            return false;
        }
        return true;
    }

    /**
     * Get availability message for customers
     */
    /**
     * Get availability message for customers (accessor: $food->availability_message)
     */
    public function getAvailabilityMessageAttribute(): ?string
    {
        if ($this->is_in_stock) {
            return null; // Available
        }

        if ($this->available_at && $this->available_at->isFuture()) {
            $diff = now()->diff($this->available_at);
            if ($diff->days > 0) {
                return 'Available ' . $this->available_at->format('D, g:i A');
            }
            if ($diff->h > 0) {
                return 'Available in ' . $diff->h . 'h ' . $diff->i . 'm';
            }
            return 'Available in ' . $diff->i . ' minutes';
        }

        return 'Currently unavailable';
    }

    /**
     * Get available_at as ISO string for JS countdown
     */
    public function getAvailableAtIsoAttribute(): ?string
    {
        return $this->available_at ? $this->available_at->toIso8601String() : null;
    }
}