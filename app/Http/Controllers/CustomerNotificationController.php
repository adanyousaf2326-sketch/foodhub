<?php

namespace App\Http\Controllers;

use App\Models\CustomerNotification;
use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    protected function getCustomerId()
    {
        return session('customer_id');
    }

    /**
     * Get unread count (for bell badge)
     */
    public function unreadCount()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['count' => 0]);

        $count = CustomerNotification::unreadCount($customerId);
        return response()->json(['count' => $count]);
    }

    /**
     * Get notifications (dropdown list)
     */
    public function index()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['notifications' => []]);

        $notifications = CustomerNotification::latestForCustomer($customerId, 20);
        $unreadCount = CustomerNotification::unreadCount($customerId);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markRead($id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['error' => 'Not logged in'], 401);

        CustomerNotification::where('id', $id)
            ->where('customer_id', $customerId)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read
     */
    public function markAllRead()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['error' => 'Not logged in'], 401);

        CustomerNotification::where('customer_id', $customerId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
