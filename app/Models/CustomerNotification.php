<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    protected $table = 'customer_notifications';

    protected $fillable = [
        'customer_id', 'title', 'message', 'type', 'data', 'is_read', 'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Create notification for ALL customers (new deal alert)
     */
    public static function notifyAllCustomers($title, $message, $type = 'deal', $data = [])
    {
        $customers = Customer::pluck('id');

        foreach ($customers as $customerId) {
            static::create([
                'customer_id' => $customerId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => $data,
            ]);
        }

        return $customers->count();
    }

    /**
     * Get unread count for a customer
     */
    public static function unreadCount($customerId)
    {
        return static::where('customer_id', $customerId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get latest notifications for a customer
     */
    public static function latestForCustomer($customerId, $limit = 10)
    {
        return static::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
