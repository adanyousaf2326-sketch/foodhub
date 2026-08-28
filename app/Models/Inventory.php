<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'food_id',
        'stock_quantity',
        'low_stock_threshold',
        'track_stock',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_stock' => 'boolean',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function isOutOfStock(): bool
    {
        return $this->track_stock && $this->stock_quantity <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->track_stock && $this->stock_quantity > 0 && $this->stock_quantity <= $this->low_stock_threshold;
    }
}
