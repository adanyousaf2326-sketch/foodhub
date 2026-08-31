<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodSize extends Model
{
    protected $fillable = [
        'food_id',
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
