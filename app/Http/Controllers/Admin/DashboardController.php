<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Cache counts for 60 seconds to reduce DB load with many users
        $totalCategories = \Cache::remember('stats_categories', 60, fn() => Category::count());
        $totalFood = \Cache::remember('stats_food', 60, fn() => Food::count());
        $availableFood = \Cache::remember('stats_available_food', 60, fn() => Food::where('is_available', 1)->count());

        $dateQuery = Order::query();

        if ($request->filled('from_date')) {
            $fromTime = $request->input('from_time', '00:00');
            $from = Carbon::parse($request->from_date . ' ' . $fromTime);
            $dateQuery->where('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $dateQuery->where('created_at', '<=', $to);
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $dateQuery->whereDate('created_at', today());
        }

        $totalOrders = (clone $dateQuery)->count();
        $pendingOrders = (clone $dateQuery)->where('status', 'Pending')->count();
        $completedOrders = (clone $dateQuery)->whereIn('status', ['Completed', 'Delivered'])->count();
        $totalRevenue = (clone $dateQuery)->whereIn('status', ['Completed', 'Delivered'])->sum('total_amount');
        $todayRevenue = $totalRevenue;
        $dineInOrders = (clone $dateQuery)->where('order_type', 'Dine In')->count();
        $takeAwayOrders = (clone $dateQuery)->whereIn('order_type', ['Takeaway', 'Take Away', 'TakeAway'])->count();
        $deliveryOrders = (clone $dateQuery)->where('order_type', 'Delivery')->count();

        $ordersQuery = Order::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('order_type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
                if (ctype_digit($search)) {
                    $query->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('from_date')) {
            $fromTime = $request->input('from_time', '00:00');
            $from = Carbon::parse($request->from_date . ' ' . $fromTime);
            $ordersQuery->where('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $ordersQuery->where('created_at', '<=', $to);
        }

        if (!$request->filled('search') && !$request->filled('from_date') && !$request->filled('to_date')) {
            $ordersQuery->whereDate('created_at', today());
        }

        $recentOrders = $ordersQuery->latest()->get();
        $filteredOrdersCount = $recentOrders->count();
        $filteredOrdersTotal = $recentOrders->whereIn('status', ['Completed', 'Delivered'])->sum('total_amount');

        return view('admin.dashboard', compact(
            'totalCategories', 'totalFood', 'availableFood',
            'totalOrders', 'pendingOrders', 'completedOrders',
            'totalRevenue', 'todayRevenue', 'dineInOrders',
            'takeAwayOrders', 'filteredOrdersTotal', 'deliveryOrders',
            'recentOrders', 'filteredOrdersCount'
        ));
    }

    public function ordersJson(Request $request)
    {
        $query = Order::query();

        if ($request->filled('from_date')) {
            $fromTime = $request->input('from_time', '00:00');
            $from = Carbon::parse($request->from_date . ' ' . $fromTime);
            $query->where('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $query->where('created_at', '<=', $to);
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $query->whereDate('created_at', today());
        }

        $pendingCount = (clone $query)->where('status', 'Pending')->count();
        $totalCount = (clone $query)->count();
        $completedCount = (clone $query)->whereIn('status', ['Completed', 'Delivered'])->count();
        $cancelledCount = (clone $query)->where('status', 'Cancelled')->count();
        $preparingCount = (clone $query)->where('status', 'Preparing')->count();
        $revenue = (clone $query)->whereIn('status', ['Completed', 'Delivered'])->sum('total_amount');

        $recentOrders = (clone $query)->latest()->limit(50)->get([
            'id', 'customer_name', 'phone', 'order_type',
            'total_amount', 'payment_method', 'status', 'created_at'
        ]);

        $recentChanges = Order::latest()
            ->limit(10)
            ->get(['id', 'customer_name', 'status', 'order_type', 'total_amount', 'updated_at', 'created_at']);

        return response()->json([
            'pending_count' => $pendingCount,
            'total_count' => $totalCount,
            'completed_count' => $completedCount,
            'cancelled_count' => $cancelledCount,
            'preparing_count' => $preparingCount,
            'revenue' => $revenue,
            'orders' => $recentOrders,
            'recent_changes' => $recentChanges,
        ]);
    }

    public function notificationsJson()
    {
        // Unread customer messages
        $unreadMessages = \App\Models\Message::where('sender_type', 'customer')
            ->where('is_read', false)
            ->with('order')
            ->latest()
            ->get();

        // Pending edit requests (shown in messages section only, NOT in bell)
        $pendingEditRequests = \App\Models\OrderEditRequest::where('status', 'pending')
            ->with('order')
            ->latest()
            ->get();

        // Recently updated orders (customer edited their order — show in bell)
        $recentlyUpdated = Order::where('updated_at', '>', now()->subMinutes(5))
            ->whereColumn('updated_at', '>', 'created_at')
            ->whereNotIn('status', ['Cancelled', 'Completed', 'Delivered'])
            ->latest('updated_at')
            ->limit(10)
            ->get(['id', 'customer_name', 'order_type', 'total_amount', 'status', 'updated_at', 'created_at']);

        return response()->json([
            'unread_messages' => $unreadMessages,
            'unread_messages_count' => $unreadMessages->count(),
            'edit_requests' => $pendingEditRequests,
            'recently_updated_orders' => $recentlyUpdated,
        ]);
    }

    /*
     * Analytics JSON for Chart.js dashboard charts
     */
    public function analyticsJson(Request $request)
    {
        $range = $request->query('range', '7days'); // 7days, 30days, 12months

        // --- Revenue Trend (line chart) ---
        if ($range === '12months') {
            $revenueData = Order::whereIn('status', ['Completed', 'Delivered'])
                ->where('created_at', '>=', now()->subMonths(12))
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as label, SUM(total_amount) as total, COUNT(*) as count')
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } elseif ($range === '30days') {
            $revenueData = Order::whereIn('status', ['Completed', 'Delivered'])
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as label, SUM(total_amount) as total, COUNT(*) as count')
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } else {
            $revenueData = Order::whereIn('status', ['Completed', 'Delivered'])
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(created_at) as label, SUM(total_amount) as total, COUNT(*) as count')
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        }

        // --- Order Status Distribution (doughnut chart) ---
        $statusData = Order::where('created_at', '>=', now()->subDays($range === '12months' ? 365 : ($range === '30days' ? 30 : 7)))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // --- Top Selling Items ---
        $topSelling = \App\Models\OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['Completed', 'Delivered'])
            ->where('orders.created_at', '>=', now()->subDays($range === '12months' ? 365 : ($range === '30days' ? 30 : 7)))
            ->selectRaw('order_items.food_name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.food_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // --- Order Type Distribution (pie chart) ---
        $typeData = Order::where('created_at', '>=', now()->subDays($range === '12months' ? 365 : ($range === '30days' ? 30 : 7)))
            ->selectRaw('order_type, COUNT(*) as count')
            ->groupBy('order_type')
            ->get();

        // --- Average Rating ---
        $avgRating = \App\Models\Rating::avg('stars');
        $totalRatings = \App\Models\Rating::count();

        return response()->json([
            'revenue_trend' => $revenueData,
            'status_distribution' => $statusData,
            'top_selling' => $topSelling,
            'type_distribution' => $typeData,
            'avg_rating' => round($avgRating ?? 0, 1),
            'total_ratings' => $totalRatings,
        ]);
    }

    /*
     * Server-Sent Events for real-time dashboard updates
     */
    public function streamUpdates(Request $request)
    {
        $request->validate([
            'last_order_id' => 'nullable|integer',
        ]);

        $lastOrderId = $request->input('last_order_id', 0);

        return response()->stream(
            function () use ($lastOrderId) {
                $newOrders = Order::where('id', '>', $lastOrderId)
                    ->latest()
                    ->get();

                if ($newOrders->isNotEmpty()) {
                    echo "data: " . json_encode([
                        'type' => 'new_orders',
                        'orders' => $newOrders->toArray(),
                        'count' => $newOrders->count(),
                        'last_id' => $newOrders->first()->id,
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                }

                // Also check for status changes
                $statusChanges = Order::where('updated_at', '>', now()->subSeconds(10))
                    ->whereColumn('updated_at', '>', 'created_at')
                    ->where('id', '>', $lastOrderId)
                    ->latest('updated_at')
                    ->get();

                if ($statusChanges->isNotEmpty()) {
                    echo "data: " . json_encode([
                        'type' => 'status_changes',
                        'orders' => $statusChanges->toArray(),
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                }

                sleep(3);
            },
            200,
            [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no',
            ]
        );
    }

    /*
     * API: Get latest orders for polling (alternative to SSE)
     */
    public function latestOrdersJson(Request $request)
    {
        $lastOrderId = $request->input('last_order_id', 0);
        $lastUpdated = $request->input('last_updated');

        $query = Order::query();

        if ($lastOrderId > 0) {
            $query->where('id', '>', $lastOrderId);
        }

        if ($lastUpdated) {
            $query->orWhere('updated_at', '>', $lastUpdated);
        }

        $newOrders = $query->latest()
            ->limit(20)
            ->get([
                'id', 'customer_name', 'phone', 'email', 'order_type',
                'total_amount', 'payment_method', 'status', 'created_at', 'updated_at'
            ]);

        $pendingCount = Order::where('status', 'Pending')->count();
        $preparingCount = Order::where('status', 'Preparing')->count();

        return response()->json([
            'orders' => $newOrders,
            'pending_count' => $pendingCount,
            'preparing_count' => $preparingCount,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    /*
     * Kitchen Display System
     */
    public function kitchen()
    {
        $activeStatuses = ['Pending', 'Assigned', 'Picked Up', 'Out for Delivery'];

        $pendingOrders = Order::whereIn('status', ['Pending', 'Assigned'])
            ->with(['items.food', 'table', 'rider'])
            ->latest()
            ->get();

        $pickedUpOrders = Order::whereIn('status', ['Picked Up', 'Out for Delivery'])
            ->with(['items.food', 'table', 'rider'])
            ->latest()
            ->get();

        $readyOrders = Order::where('status', 'Delivered')
            ->whereDate('updated_at', today())
            ->with(['rider'])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $cancelledOrders = Order::where('status', 'Cancelled')
            ->whereDate('updated_at', today())
            ->with(['rider'])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('admin.kitchen', compact('pendingOrders', 'pickedUpOrders', 'readyOrders', 'cancelledOrders'));
    }

    /**
     * Close All — Full daily reset in one click
     */
    public function closeAll()
    {
        // Auto-create columns if missing (SQLite fix)
        $this->ensureFoodColumns();

        $log = [];

        // 1. 🛵 Turn off ALL riders
        $ridersOff = \App\Models\Rider::where('is_on_duty', true)->update(['is_on_duty' => false]);
        $log[] = "🛵 {$ridersOff} riders turned OFF";

        // 2. 📋 Reset Assigned orders (not picked up) → Pending
        $assignedReset = Order::where('status', 'Assigned')
            ->whereNull('picked_up_at')
            ->update(['status' => 'Pending', 'rider_id' => null]);
        $log[] = "📋 {$assignedReset} assigned orders → Pending";

        // 3. 💰 Mark Cash Pending → Delivered (closed)
        $cashClosed = Order::where('status', 'Cash Pending')
            ->update(['status' => 'Delivered']);
        $log[] = "💰 {$cashClosed} cash pending → Delivered";

        // 4. ✅ Mark Picked Up / Out for Delivery (stuck orders) → Delivered
        $stuckClosed = Order::whereIn('status', ['Picked Up', 'Out for Delivery'])
            ->update(['status' => 'Delivered']);
        $log[] = "✅ {$stuckClosed} stuck orders → Delivered";

        // 5. 🍕 Re-enable ALL disabled food items
        \App\Models\Food::where('is_in_stock', false)->update(['is_in_stock' => true]);
        // Try to clear available_at if column exists
        try {
            \App\Models\Food::where('is_in_stock', true)->update(['available_at' => null]);
        } catch (\Exception $e) { /* column may not exist */ }
        $log[] = "🍕 Food items re-enabled";

        // 6. 🪑 Reset all occupied tables → available
        try {
            $tablesReset = \App\Models\RestaurantTable::where('status', 'occupied')
                ->update(['status' => 'available']);
            $log[] = "🪑 {$tablesReset} tables freed";
        } catch (\Exception $e) { /* table may not exist */ }

        // 7. 🗑️ Clear all caches
        \Cache::forget('home_foods');
        \Cache::forget('home_categories');
        \Cache::forget('home_announcements');
        \Cache::forget('stats_categories');
        \Cache::forget('stats_food');
        \Cache::forget('stats_available_food');
        $log[] = "🗑️ All caches cleared";

        $summary = implode(' • ', $log);

        return back()->with('success', "✅ New Day Started! {$summary}");
    }

    /**
     * Ensure food table has stock columns (SQLite auto-create)
     */
    protected function ensureFoodColumns()
    {
        $columns = \DB::select('PRAGMA table_info(food)');
        $columnNames = array_column($columns, 'name');

        if (!in_array('stock_quantity', $columnNames)) {
            \DB::statement('ALTER TABLE food ADD COLUMN stock_quantity INTEGER NOT NULL DEFAULT -1');
        }
        if (!in_array('is_in_stock', $columnNames)) {
            \DB::statement('ALTER TABLE food ADD COLUMN is_in_stock BOOLEAN NOT NULL DEFAULT 1');
        }
        if (!in_array('low_stock_threshold', $columnNames)) {
            \DB::statement('ALTER TABLE food ADD COLUMN low_stock_threshold INTEGER NOT NULL DEFAULT 5');
        }
        if (!in_array('available_at', $columnNames)) {
            \DB::statement('ALTER TABLE food ADD COLUMN available_at TIMESTAMP NULL DEFAULT NULL');
        }
    }
}
