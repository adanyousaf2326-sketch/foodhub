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
        $totalCategories = Category::count();
        $totalFood = Food::count();
        $availableFood = Food::where('is_available', 1)->count();

        $dateQuery = Order::query();

        if ($request->filled('from_date')) {
            $fromTime = $request->input('from_time', '00:00');
            $from = Carbon::parse($request->from_date . ' ' . $fromTime);
            $dateQuery->where('created_at',
            'updated_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $dateQuery->where('created_at',
            'updated_at', '<=', $to);
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $dateQuery->whereDate('created_at',
            'updated_at', today());
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
            $ordersQuery->where('created_at',
            'updated_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $ordersQuery->where('created_at',
            'updated_at', '<=', $to);
        }

        if (!$request->filled('search') && !$request->filled('from_date') && !$request->filled('to_date')) {
            $ordersQuery->whereDate('created_at',
            'updated_at', today());
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
            $query->where('created_at',
            'updated_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $query->where('created_at',
            'updated_at', '<=', $to);
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $query->whereDate('created_at',
            'updated_at', today());
        }

        $pendingCount = (clone $query)->where('status', 'Pending')->count();
        $totalCount = (clone $query)->count();
        $completedCount = (clone $query)->whereIn('status', ['Completed', 'Delivered'])->count();
        $cancelledCount = (clone $query)->where('status', 'Cancelled')->count();
        $preparingCount = (clone $query)->where('status', 'Preparing')->count();
        $revenue = (clone $query)->whereIn('status', ['Completed', 'Delivered'])->sum('total_amount');

        $recentOrders = (clone $query)->latest()->limit(50)->get([
            'id', 'customer_name', 'phone', 'order_type',
            'total_amount', 'payment_method', 'status', 'created_at',
            'updated_at'
        ]);

        $recentChanges = Order::latest()
            ->limit(10)
            ->get(['id', 'customer_name', 'status', 'order_type', 'total_amount', 'updated_at', 'created_at',
            'updated_at']);

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
}
