<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    // ==================== PUBLIC ====================

    public function showRegister()
    {
        return view('rider.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:riders,phone|max:20',
            'cnic' => 'required|string|unique:riders,cnic|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'rider_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('riders', $filename, 'public');
        }

        Rider::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'cnic' => $request->cnic,
            'address' => $request->address,
            'photo' => $photoPath,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        return redirect()->route('rider.login')
            ->with('success', 'Registration submitted! Please wait for admin approval.');
    }

    public function showLogin()
    {
        return view('rider.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $rider = Rider::where('phone', $request->phone)->first();

        if (!$rider || !Hash::check($request->password, $rider->password)) {
            return back()->withErrors(['phone' => 'Invalid phone or password.']);
        }

        if ($rider->status !== 'approved') {
            return back()->withErrors(['phone' => 'Your account is pending approval. Please wait.']);
        }

        // Manual session login
        Auth::guard('web')->logout();
        session()->put('rider_id', $rider->id);
        session()->put('rider_name', $rider->name);
        session()->regenerate();

        return redirect()->route('rider.dashboard');
    }

    public function logout()
    {
        session()->forget('rider_id');
        session()->forget('rider_name');
        session()->invalidate();
        return redirect()->route('rider.login');
    }

    // ==================== RIDER DASHBOARD ====================

    protected function getCurrentRider()
    {
        $riderId = session('rider_id');
        if (!$riderId) return null;
        return Rider::find($riderId);
    }

    public function dashboard()
    {
        $rider = $this->getCurrentRider();
        if (!$rider) return redirect()->route('rider.login');

        $assignedOrders = Order::where('rider_id', $rider->id)
            ->whereIn('status', ['Assigned', 'Picked Up', 'Out for Delivery'])
            ->with(['items.food', 'table'])
            ->latest()
            ->get();

        $deliveredOrders = Order::where('rider_id', $rider->id)
            ->where('status', 'Delivered')
            ->latest()
            ->limit(20)
            ->get();

        $totalDelivered = Order::where('rider_id', $rider->id)
            ->where('status', 'Delivered')
            ->count();

        return view('rider.dashboard', compact('rider', 'assignedOrders', 'deliveredOrders', 'totalDelivered'));
    }

    public function toggleDuty(Request $request)
    {
        $rider = $this->getCurrentRider();
        if (!$rider) return response()->json(['error' => 'Not logged in'], 401);

        $rider->update([
            'is_on_duty' => !$rider->is_on_duty,
            'last_active_at' => now(),
        ]);

        return response()->json([
            'is_on_duty' => $rider->is_on_duty,
            'message' => $rider->is_on_duty ? 'You are now ON DUTY!' : 'You are now OFF DUTY.',
        ]);
    }

    public function updateLocation(Request $request)
    {
        $rider = $this->getCurrentRider();
        if (!$rider) return response()->json(['error' => 'Not logged in'], 401);

        $rider->update(['last_active_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function acceptOrder($orderId)
    {
        $rider = $this->getCurrentRider();
        if (!$rider || !$rider->is_on_duty) {
            return back()->with('error', 'You must be on duty to accept orders.');
        }

        $order = Order::where('id', $orderId)
            ->where('rider_id', $rider->id)
            ->where('status', 'Assigned')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or already accepted.');
        }

        $order->update(['status' => 'Out for Delivery']);

        return back()->with('success', 'Order #' . $orderId . ' accepted! Heading to deliver.');
    }

    public function markDelivered($orderId)
    {
        $rider = $this->getCurrentRider();
        if (!$rider) return redirect()->route('rider.login');

        $order = Order::where('id', $orderId)
            ->where('rider_id', $rider->id)
            ->whereIn('status', ['Out for Delivery', 'Picked Up'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or already delivered.');
        }

        $order->update(['status' => 'Delivered']);
        $rider->increment('total_orders');

        return back()->with('success', 'Order #' . $orderId . ' delivered successfully! 🎉');
    }

    /**
     * Rider picks up order from restaurant kitchen
     */
    public function pickUp($orderId)
    {
        $rider = $this->getCurrentRider();
        if (!$rider || !$rider->is_on_duty) {
            return back()->with('error', 'You must be on duty.');
        }

        $order = Order::where('id', $orderId)
            ->where('rider_id', $rider->id)
            ->where('status', 'Assigned')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or already picked up.');
        }

        $order->update([
            'status' => 'Picked Up',
            'picked_up_at' => now(),
        ]);

        return back()->with('success', 'Order #' . $orderId . ' picked up from kitchen! 📦');
    }

    /**
     * Rider returns order to kitchen (cancelled order)
     */
    public function returnToKitchen($orderId)
    {
        $rider = $this->getCurrentRider();
        if (!$rider) return redirect()->route('rider.login');

        $order = Order::where('id', $orderId)
            ->where('rider_id', $rider->id)
            ->whereIn('status', ['Picked Up', 'Out for Delivery'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        $order->update([
            'status' => 'Cancelled',
            'returned_at' => now(),
        ]);

        // Free the table if dine-in
        if ($order->table_id) {
            \App\Models\RestaurantTable::where('id', $order->table_id)
                ->where('status', 'occupied')
                ->update(['status' => 'available']);
        }

        return back()->with('success', 'Order #' . $orderId . ' returned to kitchen and cancelled.');
    }

    // ==================== ADMIN RIDER MANAGEMENT ====================

    public function adminIndex()
    {
        $riders = Rider::withCount(['orders' => function ($q) {
            $q->where('status', 'Delivered');
        }])->latest()->get();

        $pendingCount = Rider::where('status', 'pending')->count();
        $approvedCount = Rider::where('status', 'approved')->count();
        $onDutyCount = Rider::where('status', 'approved')->where('is_on_duty', true)->count();

        return view('admin.riders', compact('riders', 'pendingCount', 'approvedCount', 'onDutyCount'));
    }

    public function approveRider($id)
    {
        Rider::where('id', $id)->update(['status' => 'approved']);
        return back()->with('success', 'Rider approved!');
    }

    public function rejectRider($id)
    {
        Rider::where('id', $id)->update(['status' => 'rejected']);
        return back()->with('success', 'Rider rejected.');
    }

    public function toggleRiderDuty($id)
    {
        $rider = Rider::findOrFail($id);
        $rider->update(['is_on_duty' => !$rider->is_on_duty]);
        return back()->with('success', $rider->name . ' is now ' . ($rider->is_on_duty ? 'ON duty' : 'OFF duty'));
    }

    public function deleteRider($id)
    {
        Rider::where('id', $id)->delete();
        return back()->with('success', 'Rider deleted.');
    }

    /**
     * Auto-assign rider to delivery order (called when order is placed)
     */
    public static function autoAssignRider(Order $order)
    {
        if ($order->order_type !== 'Delivery') return null;

        // Find an available on-duty rider (least orders first)
        $rider = Rider::where('status', 'approved')
            ->where('is_on_duty', true)
            ->orderBy('total_orders')
            ->first();

        if ($rider) {
            $order->update(['rider_id' => $rider->id, 'status' => 'Assigned']);
            return $rider;
        }

        return null;
    }
}
