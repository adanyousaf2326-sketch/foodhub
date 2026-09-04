<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Rider extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'phone', 'cnic', 'address', 'photo',
        'password', 'status', 'is_on_duty', 'total_orders', 'last_active_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_on_duty' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'rider_id');
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http')) {
                return $this->photo;
            }
            // Uploaded file path
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=ff6b00&color=fff&size=200';
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'approved' && $this->is_on_duty;
    }
}
