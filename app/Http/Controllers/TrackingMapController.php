<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rider;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class TrackingMapController extends Controller
{
    /**
     * Customer: Live order tracking map
     */
    public function customerMap($orderId)
    {
        $order = Order::with(['rider', 'table', 'items.food'])->findOrFail($orderId);

        // Get restaurant location
        $restaurantLat = 33.6844;
        $restaurantLng = 73.0479;

        return view('tracking-map', compact('order', 'restaurantLat', 'restaurantLng'));
    }

    /**
     * API: Get rider location for an order (polled by customer)
     */
    public function riderLocation($orderId)
    {
        $order = Order::with('rider')->findOrFail($orderId);

        $riderLocation = null;
        if ($order->rider && $order->rider->last_active_at && $order->rider->last_active_at->diffInMinutes(now()) < 10) {
            // Get last known location from order_locations or rider's last known
            $location = \App\Models\OrderLocation::where('order_id', $orderId)
                ->latest()
                ->first();

            if ($location && $location->latitude && $location->longitude) {
                $riderLocation = [
                    'lat' => (float) $location->latitude,
                    'lng' => (float) $location->longitude,
                ];
            }
        }

        return response()->json([
            'order_status' => $order->status,
            'rider_name' => $order->rider?->name,
            'rider_phone' => $order->rider?->phone,
            'rider_location' => $riderLocation,
            'restaurant_lat' => 33.6844,
            'restaurant_lng' => 73.0479,
            'customer_lat' => $order->customer_lat ? (float) $order->customer_lat : null,
            'customer_lng' => $order->customer_lng ? (float) $order->customer_lng : null,
            'delivery_distance_km' => $order->delivery_distance_km,
            'picked_up_at' => $order->picked_up_at,
        ]);
    }

    /**
     * API: Rider updates their location (called from rider dashboard)
     */
    public function updateRiderLocation(Request $request)
    {
        $riderId = session('rider_id');
        if (!$riderId) return response()->json(['error' => 'Not logged in'], 401);

        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        // Update rider's last active time
        Rider::where('id', $riderId)->update(['last_active_at' => now()]);

        // Update location for active orders
        $activeOrders = Order::where('rider_id', $riderId)
            ->whereIn('status', ['Assigned', 'Picked Up', 'Out for Delivery'])
            ->get();

        foreach ($activeOrders as $order) {
            \App\Models\OrderLocation::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'latitude' => $request->lat,
                    'longitude' => $request->lng,
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Admin: Live rider tracking map
     */
    public function adminRiderMap()
    {
        $riders = Rider::where('status', 'approved')
            ->where('is_on_duty', true)
            ->with(['orders' => function ($q) {
                $q->whereIn('status', ['Assigned', 'Picked Up', 'Out for Delivery']);
            }])
            ->get();

        $restaurantLat = 33.6844;
        $restaurantLng = 73.0479;

        return view('admin.rider-map', compact('riders', 'restaurantLat', 'restaurantLng'));
    }

    /**
     * API: Get all on-duty rider locations (polled by admin)
     */
    public function allRiderLocations()
    {
        $riders = Rider::where('status', 'approved')
            ->where('is_on_duty', true)
            ->with(['orders' => function ($q) {
                $q->whereIn('status', ['Assigned', 'Picked Up', 'Out for Delivery']);
            }])
            ->get()
            ->map(function ($rider) {
                $activeOrder = $rider->orders->first();
                $location = null;

                if ($activeOrder) {
                    $loc = \App\Models\OrderLocation::where('order_id', $activeOrder->id)
                        ->latest()
                        ->first();

                    if ($loc && $loc->latitude) {
                        $location = ['lat' => (float) $loc->latitude, 'lng' => (float) $loc->longitude];
                    }
                }

                return [
                    'id' => $rider->id,
                    'name' => $rider->name,
                    'phone' => $rider->phone,
                    'photo' => $rider->photo_url,
                    'location' => $location,
                    'active_order_id' => $activeOrder?->id,
                    'active_order_status' => $activeOrder?->status,
                    'active_order_customer' => $activeOrder?->customer_name,
                    'last_active' => $rider->last_active_at?->diffForHumans(),
                ];
            });

        return response()->json(['riders' => $riders]);
    }
}
