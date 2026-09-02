<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\Food;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query();

        $type = $request->query('type');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        if ($type) {
            $normalizedType = strtolower(str_replace(' ', '', $type));

            if ($normalizedType === 'takeaway') {
                $orders->whereIn('order_type', [
                    'Takeaway',
                    'Take Away',
                    'TakeAway',
                ]);
            } else {
                $orders->where('order_type', $type);
            }
        }

        if ($fromDate) {
            $fromTime = $request->query('from_time', '00:00');

            $from = Carbon::parse(
                $fromDate . ' ' . $fromTime
            );

            $orders->where('created_at', '>=', $from);
        }

        if ($toDate) {
            $toTime = $request->query('to_time', '23:59:59');

            $to = Carbon::parse(
                $toDate . ' ' . $toTime
            );

            $orders->where('created_at', '<=', $to);
        }

        if (!$fromDate && !$toDate) {
            $orders->whereDate('created_at', today());
        }

        $orders = $orders
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function track()
    {
        return view('track-order');
    }

    public function trackSearch(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
        ]);

        $order = Order::where('id', $request->order_number)
            ->with('items.food')
            ->first();

        return view('track-order', compact('order'));
    }

    /*
     * Customer Order History — search by phone number with filters
     */
    public function orderHistory(Request $request)
    {
        $orders = collect();

        if ($request->has('phone')) {
            $phone = trim($request->input('phone'));

            $query = Order::where('phone', $phone)
                ->with('items.food')
                ->latest();

            if ($request->filled('from_date')) {
                $query->where('created_at', '>=', Carbon::parse($request->from_date));
            }

            if ($request->filled('to_date')) {
                $query->where('created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('order_type')) {
                $query->where('order_type', $request->order_type);
            }

            $orders = $query->get();
        }

        return view('order-history', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.food');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Preparing,Out for Delivery,Delivered,Completed,Cancelled',
        ]);

        $this->changeStatus($order, $request->status);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /*
     * ADMIN: Edit Order (unlimited before delivery/completion, with reason)
     */
    public function adminEdit(Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'This order is already ' . $order->status . '. Cannot edit.');
        }

        $order->load(['items.food', 'table']);

        $availableFoods = Food::where('is_available', true)
            ->with(['category', 'variations'])
            ->orderBy('name')
            ->get();

        $tables = RestaurantTable::where('status', 'available')
            ->orWhere('id', $order->table_id)
            ->orderBy('table_number')
            ->get();

        return view('admin.orders.admin-edit', compact('order', 'availableFoods', 'tables'));
    }

    public function adminEditSave(Request $request, Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'This order is already ' . $order->status . '. Cannot edit.');
        }

        $request->validate([
            'edit_reason' => 'required|string|max:500',
            'order_type' => 'required|in:Delivery,Dine In,Takeaway',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required_if:order_type,Delivery|nullable|string',
            'table_id' => 'required_if:order_type,Dine In|nullable|exists:restaurant_tables,id',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'nullable|exists:food,id',
            'items.*.food_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        try {
            DB::transaction(function () use ($request, $order) {
                $newTableId = null;

                if ($request->order_type === 'Dine In') {
                    $requestedTableId = (int) $request->table_id;

                    if ($order->table_id !== $requestedTableId) {
                        if ($order->table_id) {
                            RestaurantTable::where('id', $order->table_id)
                                ->update(['status' => 'available']);
                        }
                        $table = RestaurantTable::where('id', $requestedTableId)
                            ->where('status', 'available')
                            ->lockForUpdate()
                            ->first();
                        if (!$table) {
                            throw new \Exception('Selected table is currently not available.');
                        }
                        $table->update(['status' => 'occupied']);
                        $newTableId = $table->id;
                    } else {
                        $newTableId = $order->table_id;
                    }
                } else {
                    if ($order->order_type === 'Dine In' && $order->table_id) {
                        RestaurantTable::where('id', $order->table_id)
                            ->update(['status' => 'available']);
                    }
                }

                $total = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $qty = (int) $item['quantity'];
                    $price = (float) $item['price'];
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                    $itemsData[] = [
                        'order_id' => $order->id,
                        'food_id' => !empty($item['food_id']) ? (int) $item['food_id'] : null,
                        'food_name' => $item['food_name'],
                        'price' => $price,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $order->update([
                    'customer_name' => $request->customer_name,
                    'phone' => $request->phone,
                    'address' => $request->order_type === 'Delivery' ? $request->address : null,
                    'total_amount' => $total,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'order_type' => $request->order_type,
                    'table_id' => $newTableId,
                ]);

                $order->items()->delete();
                OrderItem::insert($itemsData);

                // Log the admin edit reason as a message in chat
                \App\Models\Message::create([
                    'order_id' => $order->id,
                    'sender_type' => 'admin',
                    'sender_name' => auth()->user()->name ?? 'Admin',
                    'message' => '✏️ Admin edited this order. Reason: ' . $request->edit_reason,
                ]);
            });

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order updated successfully by admin.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['table_id' => $e->getMessage()]);
        }
    }

    public function close(Order $order)
    {
        $this->changeStatus($order, 'Completed');

        return redirect()
            ->route('admin.orders.index', [
                'type' => $order->order_type,
                'from_date' => $order->created_at->toDateString(),
                'to_date' => $order->created_at->toDateString(),
            ])
            ->with('success', 'Order closed successfully.');
    }

    public function cancel(Order $order)
    {
        $this->changeStatus($order, 'Cancelled');

        return redirect()
            ->route('admin.orders.index', [
                'type' => $order->order_type,
                'from_date' => $order->created_at->toDateString(),
                'to_date' => $order->created_at->toDateString(),
            ])
            ->with('success', 'Order cancelled successfully.');
    }

    private function changeStatus(Order $order, string $status): void
    {
        $order->update([
            'status' => $status,
        ]);

        if (
            $order->order_type === 'Dine In'
            && in_array($status, ['Completed', 'Cancelled'])
            && $order->table_id
        ) {
            $table = RestaurantTable::find($order->table_id);

            if ($table) {
                $table->update([
                    'status' => 'available',
                ]);
            }
        }

        /* SEND STATUS CHANGE NOTIFICATION + EMAIL */
        $statusMessages = [
            'Preparing' => 'Your order is now being prepared!',
            'Out for Delivery' => 'Your order is on the way!',
            'Delivered' => 'Your order has been delivered. Enjoy your meal!',
            'Completed' => 'Your order is completed. Thank you!',
            'Cancelled' => 'Your order has been cancelled.',
        ];

        $message = $statusMessages[$status] ?? "Your order status has been updated to: {$status}";

        \App\Models\Notification::create([
            'order_id' => $order->id,
            'type' => 'status_changed',
            'title' => 'Order #' . $order->id . ' — ' . $status,
            'message' => $message,
            'email' => $order->email,
        ]);

        /* Send email if customer provided email */
        if ($order->email) {
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Hello {$order->customer_name},\n\n"
                    . "Order #{$order->id} Status Update:\n"
                    . "Status: {$status}\n"
                    . "{$message}\n\n"
                    . "Track your order: " . route('track.order.search', ['order_number' => $order->id]) . "\n\n"
                    . "Thank you for choosing FoodHub!",
                    function ($msg) use ($order, $status) {
                        $msg->to($order->email)
                            ->subject('FoodHub — Order #' . $order->id . ' is ' . $status);
                    }
                );
                $order->notifications()->where('type', 'status_changed')->latest()->update(['email_sent' => true]);
            } catch (\Exception $e) {
                // Email failed — log it but don't break the flow
            }
        }
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function bill(Order $order)
    {
        $order->load('items.food');

        if ($order->status === 'Cancelled') {
            return redirect()
                ->route('admin.orders.index', [
                    'type' => $order->order_type,
                    'from_date' => $order->created_at->toDateString(),
                    'to_date' => $order->created_at->toDateString(),
                ])
                ->with('success', 'Cancelled orders cannot be billed.');
        }

        if ($order->status === 'Completed') {
            return redirect()
                ->route('admin.orders.index', [
                    'type' => $order->order_type,
                    'from_date' => $order->created_at->toDateString(),
                    'to_date' => $order->created_at->toDateString(),
                ])
                ->with('success', 'This bill is already closed.');
        }

        return view('admin.orders.bill', compact('order'));
    }

    public function completePayment(Request $request, Order $order)
    {
        if (in_array($order->status, ['Completed', 'Cancelled'])) {
            return redirect()
                ->route('admin.orders.index', [
                    'type' => $order->order_type,
                    'from_date' => $order->created_at->toDateString(),
                    'to_date' => $order->created_at->toDateString(),
                ])
                ->with('success', 'This order cannot be billed again.');
        }

        $request->validate([
            'paid_amount' => [
                'required',
                'numeric',
                'min:' . $order->total_amount,
            ],
        ]);

        $paidAmount = (float) $request->paid_amount;

        $changeAmount = $paidAmount - (float) $order->total_amount;

        $order->update([
            'status' => 'Completed',
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'paid_at' => now(),
        ]);

        if ($order->order_type === 'Dine In' && $order->table_id) {
            $table = RestaurantTable::find($order->table_id);

            if ($table) {
                $table->update([
                    'status' => 'available',
                ]);
            }
        }

        return redirect()
            ->route('admin.orders.index', [
                'type' => $order->order_type,
                'from_date' => $order->created_at->toDateString(),
                'to_date' => $order->created_at->toDateString(),
            ])
            ->with('success', 'Bill closed successfully. Sale added to revenue.');
    }

    public function cancelFromTracking(Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed'])) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with('error', 'This order cannot be cancelled.');
        }

        if ($order->created_at->lt(now()->subMinutes(15))) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'error',
                    'Cancellation time expired. Orders can only be cancelled within 15 minutes.'
                );
        }

        $order->update([
            'status' => 'Cancelled',
        ]);

        if ($order->order_type === 'Dine In' && $order->table_id) {
            $table = RestaurantTable::find($order->table_id);

            if ($table) {
                $table->update([
                    'status' => 'available',
                ]);
            }
        }

        return redirect()
            ->route('track.order.search', [
                'order_number' => $order->id,
            ])
            ->with('success', 'Your order has been cancelled successfully.');
    }

    public function trackEdit(Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'error',
                    'This order cannot be modified. It is already ' . $order->status . '.'
                );
        }

        // Customer can only edit once
        if ($order->has_edited) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'error',
                    'You have already edited this order once. You cannot edit it again. Please contact the restaurant directly if you need further changes.'
                );
        }

        // Check for admin-approved edit request (15 min window from acceptance)
        $approvedRequest = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'accepted')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        // Also allow original 15-min window from order creation
        $withinOriginalWindow = $order->created_at->gt(now()->subMinutes(15));

        if (!$approvedRequest && !$withinOriginalWindow) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'error',
                    'Order update time expired. Please send an edit request to the admin for approval.'
                );
        }

        // Use the approved request deadline if available
        if ($approvedRequest) {
            $deadline = $approvedRequest->expires_at;
            $remainingSeconds = max(0, $deadline->diffInSeconds(now(), false) * -1);
        } else {
            $deadline = $order->created_at->copy()->addMinutes(15);
            $remainingSeconds = max(0, $deadline->diffInSeconds(now(), false) * -1);
        }

        $order->load(['items.food', 'table']);

        $availableFoods = Food::where('is_available', true)
            ->with(['category', 'variations'])
            ->orderBy('name')
            ->get();

        $tables = RestaurantTable::where('status', 'available')
            ->orWhere('id', $order->table_id)
            ->orderBy('table_number')
            ->get();

        return view(
            'order-edit',
            compact(
                'order',
                'availableFoods',
                'tables',
                'deadline',
                'remainingSeconds'
            )
        );
    }

    public function trackUpdate(Request $request, Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'error',
                    'This order cannot be modified in its current status (' . $order->status . ').'
                );
        }

        // Check for admin-approved edit request
        $approvedRequest = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'accepted')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        $withinOriginalWindow = $order->created_at->gt(now()->subMinutes(15));

        if (!$approvedRequest && !$withinOriginalWindow) {
            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'error',
                    'Order update time expired. Please send an edit request to the admin for approval.'
                );
        }

        $request->validate([
            'order_type' => 'required|in:Delivery,Dine In,Takeaway',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required_if:order_type,Delivery|nullable|string',
            'table_id' => 'required_if:order_type,Dine In|nullable|exists:restaurant_tables,id',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'nullable|exists:food,id',
            'items.*.food_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        try {
            DB::transaction(function () use ($request, $order) {
                $newTableId = null;

                if ($request->order_type === 'Dine In') {
                    $requestedTableId = (int) $request->table_id;

                    if ($order->table_id !== $requestedTableId) {
                        if ($order->table_id) {
                            RestaurantTable::where('id', $order->table_id)
                                ->update([
                                    'status' => 'available',
                                ]);
                        }

                        $table = RestaurantTable::where('id', $requestedTableId)
                            ->where('status', 'available')
                            ->lockForUpdate()
                            ->first();

                        if (!$table) {
                            throw new \Exception(
                                'Selected table is currently not available. Please pick another table.'
                            );
                        }

                        $table->update([
                            'status' => 'occupied',
                        ]);

                        $newTableId = $table->id;
                    } else {
                        $newTableId = $order->table_id;
                    }
                } else {
                    if ($order->order_type === 'Dine In' && $order->table_id) {
                        RestaurantTable::where('id', $order->table_id)
                            ->update([
                                'status' => 'available',
                            ]);
                    }
                }

                $total = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $qty = (int) $item['quantity'];
                    $price = (float) $item['price'];
                    $subtotal = $price * $qty;

                    $total += $subtotal;

                    $itemsData[] = [
                        'order_id' => $order->id,
                        'food_id' => !empty($item['food_id'])
                            ? (int) $item['food_id']
                            : null,
                        'food_name' => $item['food_name'],
                        'price' => $price,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $order->update([
                    'customer_name' => $request->customer_name,
                    'phone' => $request->phone,
                    'address' => $request->order_type === 'Delivery'
                        ? $request->address
                        : null,
                    'total_amount' => $total,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'order_type' => $request->order_type,
                    'table_id' => $newTableId,
                    'has_edited' => true,
                ]);

                $order->items()->delete();

                OrderItem::insert($itemsData);
            });

            return redirect()
                ->route('track.order.search', [
                    'order_number' => $order->id,
                ])
                ->with(
                    'success',
                    'Your order #' . $order->id . ' has been updated successfully!'
                );
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'table_id' => $e->getMessage(),
                ]);
        }
    }
    public function exportCsv(Request $request)
    {
        $orders = Order::query();

        $type = $request->query('type');
        if ($type) {
            $normalizedType = strtolower(str_replace(' ', '', $type));
            if ($normalizedType === 'takeaway') {
                $orders->whereIn('order_type', ['Takeaway', 'Take Away', 'TakeAway']);
            } else {
                $orders->where('order_type', $type);
            }
        }

        if ($request->filled('from_date')) {
            $fromTime = $request->query('from_time', '00:00');
            $from = Carbon::parse($request->from_date . ' ' . $fromTime);
            $orders->where('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->query('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $orders->where('created_at', '<=', $to);
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $orders->whereDate('created_at', today());
        }

        $orders = $orders->latest()->get();

        $filename = 'foodhub-orders-' . now()->format('Y-m-d-H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Order ID', 'Customer', 'Phone', 'Order Type', 'Total (Rs.)', 'Payment', 'Status', 'Date', 'Time']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->customer_name,
                    $order->phone,
                    $order->order_type,
                    number_format($order->total_amount, 2),
                    $order->payment_method,
                    $order->status,
                    $order->created_at->format('d M Y'),
                    $order->created_at->format('h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $orders = Order::query();

        $type = $request->query('type');
        if ($type) {
            $normalizedType = strtolower(str_replace(' ', '', $type));
            if ($normalizedType === 'takeaway') {
                $orders->whereIn('order_type', ['Takeaway', 'Take Away', 'TakeAway']);
            } else {
                $orders->where('order_type', $type);
            }
        }

        if ($request->filled('from_date')) {
            $fromTime = $request->query('from_time', '00:00');
            $from = Carbon::parse($request->from_date . ' ' . $fromTime);
            $orders->where('created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $toTime = $request->query('to_time', '23:59:59');
            $to = Carbon::parse($request->to_date . ' ' . $toTime);
            $orders->where('created_at', '<=', $to);
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $orders->whereDate('created_at', today());
        }

        $orders = $orders->latest()->get();

        $totalRevenue = $orders->whereIn('status', ['Completed', 'Delivered'])->sum('total_amount');

        $dateRange = 'Today';
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from = $request->filled('from_date') ? $request->from_date : 'Start';
            $to = $request->filled('to_date') ? $request->to_date : 'Now';
            $dateRange = $from . ' to ' . $to;
        }

        return view('admin.orders.export-pdf', compact('orders', 'totalRevenue', 'dateRange'));
    }

    /*
     * CUSTOMER: Submit Edit Request
     */
    public function storeEditRequest(Request $request, Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return back()->with('error', 'This order cannot be edited. It is already ' . $order->status . '.');
        }

        // Customer can only edit once
        if ($order->has_edited) {
            return back()->with('error', 'You have already edited this order once. You cannot edit it again. Please contact the restaurant directly if you need further changes.');
        }

        $existingPending = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return back()->with('error', 'You already have a pending edit request. Please wait for admin approval.');
        }

        $existingAccepted = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'accepted')
            ->where('expires_at', '>', now())
            ->exists();

        if ($existingAccepted) {
            return redirect()->route('track.order.edit', $order);
        }

        \App\Models\OrderEditRequest::create([
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
            'phone' => $order->phone,
            'message' => $request->input('message', 'Customer wants to edit this order.'),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Edit request sent! You can only edit your order once, so make sure to add all changes. Waiting for admin approval.');
    }

    /*
     * CUSTOMER: Get Messages (JSON)
     */
    public function getMessages(Order $order)
    {
        $lastId = request()->input('last_id', 0);

        $messages = \App\Models\Message::where('order_id', $order->id)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /*
     * CUSTOMER: Send Message
     */
    public function sendMessage(Request $request, Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send messages for this order.',
            ], 422);
        }

        $request->validate([
            'message' => 'required|string|max:500',
            'customer_name' => 'required|string|max:255',
        ]);

        \App\Models\Message::create([
            'order_id' => $order->id,
            'sender_type' => 'customer',
            'sender_name' => $request->customer_name,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent!',
        ]);
    }

    /*
     * ADMIN: Accept Edit Request
     */
    public function acceptEditRequest(Order $order, \App\Models\OrderEditRequest $editRequest)
    {
        if ($editRequest->order_id !== $order->id) {
            return back()->with('error', 'Invalid edit request.');
        }

        // Reject other pending requests for this order
        \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('id', '!=', $editRequest->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        $editRequest->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'expires_at' => now()->addMinutes(15),
        ]);

        return back()->with('success', 'Edit request accepted! Customer can now edit the order for 15 minutes.');
    }

    /*
     * ADMIN: Reject Edit Request
     */
    public function rejectEditRequest(Order $order, \App\Models\OrderEditRequest $editRequest)
    {
        if ($editRequest->order_id !== $order->id) {
            return back()->with('error', 'Invalid edit request.');
        }

        $editRequest->update([
            'status' => 'rejected',
            'admin_response' => request()->input('admin_response', 'Request rejected by admin.'),
        ]);

        return back()->with('success', 'Edit request rejected.');
    }

    /*
     * ADMIN: Send Message
     */
    public function adminSendMessage(Request $request, Order $order)
    {
        if (in_array($order->status, ['Cancelled', 'Completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send messages for this order.',
            ], 422);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        \App\Models\Message::create([
            'order_id' => $order->id,
            'sender_type' => 'admin',
            'sender_name' => auth()->user()->name ?? 'Admin',
            'message' => $request->message,
        ]);

        // Mark customer messages as read
        \App\Models\Message::where('order_id', $order->id)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent!',
        ]);
    }

    /*
     * CUSTOMER: Check edit request status (JSON)
     */
    public function editStatus(Order $order)
    {
        $approved = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'accepted')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        $pending = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $rejected = \App\Models\OrderEditRequest::where('order_id', $order->id)
            ->where('status', 'rejected')
            ->latest()
            ->first();

        return response()->json([
            'can_edit' => $approved !== null,
            'edit_url' => $approved ? route('track.order.edit', $order) : null,
            'expires_at' => $approved ? $approved->expires_at->toISOString() : null,
            'pending' => $pending !== null,
            'rejected' => $rejected !== null && !$pending && !$approved,
        ]);
    }

    /*
     * CUSTOMER: Rate Order
     */
    public function rateOrder(Request $request, Order $order)
    {
        if ($order->status !== 'Delivered') {
            return back()->with('error', 'You can only rate delivered orders.');
        }

        $existing = \App\Models\Rating::where('order_id', $order->id)->exists();
        if ($existing) {
            return back()->with('error', 'You have already rated this order.');
        }

        $request->validate([
            'stars' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:500',
        ]);

        \App\Models\Rating::create([
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
            'stars' => $request->stars,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }

}
