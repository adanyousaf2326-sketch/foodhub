<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\Food;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  public function index(Request $request)
{
    $orders = Order::query();

    $type = $request->query('type');

    if ($type) {
        $normalizedType = strtolower(str_replace(' ', '', $type));

        if ($normalizedType === 'takeaway') {
            $orders->where('order_type', 'Takeaway');
        } else {
            $orders->where('order_type', $type);
        }
    }

    $orders = $orders->latest()->get();

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

    public function close(Order $order)
    {
        $this->changeStatus($order, 'Completed');

        return redirect()
            ->route('admin.orders.index', ['type' => $order->order_type])
            ->with('success', 'Order closed successfully.');
    }

    public function cancel(Order $order)
    {
        $this->changeStatus($order, 'Cancelled');

        return redirect()
            ->route('admin.orders.index', ['type' => $order->order_type])
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
            ->route('admin.orders.index', ['type' => $order->order_type])
            ->with('success', 'Cancelled order ka bill close nahi ho sakta.');
    }

    if ($order->status === 'Completed') {
        return redirect()
            ->route('admin.orders.index', ['type' => $order->order_type])
            ->with('success', 'This bill is already closed.');
    }

    return view('admin.orders.bill', compact('order'));
}

public function completePayment(Request $request, Order $order)
{
    if (in_array($order->status, ['Completed',  'Cancelled'])) {
        return redirect()
            ->route('admin.orders.index', ['type' => $order->order_type])
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
        $table = \App\Models\RestaurantTable::find($order->table_id);

        if ($table) {
            $table->update([
                'status' => 'available',
            ]);
        }
    }

    return redirect()
        ->route('admin.orders.index', ['type' => $order->order_type])
        ->with('success', 'Bill closed successfully. Sale added to revenue.');
}
    public function cancelFromTracking(Order $order)
{
    // Cancelled ya completed bill dobara cancel nahi hoga
    if (in_array($order->status, ['Cancelled', 'Completed'])) {
        return redirect()
            ->route('track.order.search', [
                'order_number' => $order->id,
            ])
            ->with('error', 'This order cannot be cancelled.');
    }

    // Order create hone ke 15 minutes ke baad cancel nahi hoga
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

    // Dine In order cancel ho to table available ho jaye
    if ($order->order_type === 'Dine In' && $order->table_id) {
        $table = \App\Models\RestaurantTable::find($order->table_id);

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
        // Cancelled, Completed or Delivered orders cannot be modified
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return redirect()
                ->route('track.order.search', ['order_number' => $order->id])
                ->with('error', 'This order cannot be modified in its current status (' . $order->status . ').');
        }

        // Strict 15-minute window from original order creation
        if ($order->created_at->lt(now()->subMinutes(15))) {
            return redirect()
                ->route('track.order.search', ['order_number' => $order->id])
                ->with(
                    'error',
                    'Order update time expired. Orders can only be modified within 15 minutes of initial placement.'
                );
        }

        $order->load(['items.food', 'table']);

        $availableFoods = Food::where('is_available', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        $tables = RestaurantTable::where('status', 'available')
            ->orWhere('id', $order->table_id)
            ->orderBy('table_number')
            ->get();

        $deadline = $order->created_at->copy()->addMinutes(15);
        $remainingSeconds = max(0, $deadline->diffInSeconds(now(), false) * -1);

        return view('order-edit', compact('order', 'availableFoods', 'tables', 'deadline', 'remainingSeconds'));
    }

    public function trackUpdate(Request $request, Order $order)
    {
        // Cancelled, Completed or Delivered orders cannot be modified
        if (in_array($order->status, ['Cancelled', 'Completed', 'Delivered'])) {
            return redirect()
                ->route('track.order.search', ['order_number' => $order->id])
                ->with('error', 'This order cannot be modified in its current status (' . $order->status . ').');
        }

        // Strict 15-minute window from original created_at timestamp
        if ($order->created_at->lt(now()->subMinutes(15))) {
            return redirect()
                ->route('track.order.search', ['order_number' => $order->id])
                ->with(
                    'error',
                    'Order update time expired. Orders can only be modified within 15 minutes of initial placement.'
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
                                ->update(['status' => 'available']);
                        }

                        $table = RestaurantTable::where('id', $requestedTableId)
                            ->where('status', 'available')
                            ->lockForUpdate()
                            ->first();

                        if (!$table) {
                            throw new \Exception('Selected table is currently not available. Please pick another table.');
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

                // Update order details. Note: created_at remains unchanged!
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

                // Sync items
                $order->items()->delete();
                OrderItem::insert($itemsData);
            });

            return redirect()
                ->route('track.order.search', ['order_number' => $order->id])
                ->with('success', 'Your order #' . $order->id . ' has been updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'table_id' => $e->getMessage(),
                ]);
        }
    }
}