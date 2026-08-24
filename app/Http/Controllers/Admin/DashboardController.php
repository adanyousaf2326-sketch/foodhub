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
        // Dashboard totals
        $totalCategories = Category::count();

        $totalFood = Food::count();

        $availableFood = Food::where('is_available', 1)->count();

        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 'Pending')->count();

        $completedOrders = Order::whereIn('status', [
            'Completed',
            'Delivered',
        ])->count();

        // All-time revenue
        $totalRevenue = Order::whereIn('status', [
            'Completed',
            'Delivered',
        ])->sum('total_amount');

        // Today's revenue
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereIn('status', [
                'Completed',
                'Delivered',
            ])
            ->sum('total_amount');

        // Order type totals
        $dineInOrders = Order::where('order_type', 'Dine In')->count();

        $takeAwayOrders = Order::whereIn('order_type', [
            'Takeaway',
            'Take Away',
            'TakeAway',
        ])->count();

        $deliveryOrders = Order::where('order_type', 'Delivery')->count();

        /*
         * Search and date/time range filter
         */
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

            $from = Carbon::parse(
                $request->from_date . ' ' . $fromTime
            );

            $ordersQuery->where('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->input('to_time', '23:59:59');

            $to = Carbon::parse(
                $request->to_date . ' ' . $toTime
            );

            $ordersQuery->where('created_at', '<=', $to);
        }

        // All old orders stay visible unless a filter is applied.
        $recentOrders = $ordersQuery
            ->latest()
            ->get();

        $filteredOrdersCount = $recentOrders->count();
$filteredOrdersTotal = $recentOrders
    ->whereIn('status', ['Completed', 'Delivered'])
    ->sum('total_amount');        return view('admin.dashboard', compact(
            'totalCategories',
            'totalFood',
            'availableFood',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'todayRevenue',
            'dineInOrders',
            'takeAwayOrders',
            'filteredOrdersTotal',
            'deliveryOrders',
            'recentOrders',
            'filteredOrdersCount'
        ));
    }
}