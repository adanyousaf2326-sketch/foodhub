<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('customer.register');
    }

    public function register(Request $request)
    {
        if (Customer::where('email', $request->email)->exists()) {
            return back()->withInput()
                ->with('error', 'This email is already registered! Please login.');
        }
        if (Customer::where('phone', $request->phone)->exists()) {
            return back()->withInput()
                ->with('error', 'This phone number is already registered! Please login.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Auto login with persistent session
        session()->put('customer_id', $customer->id);
        session()->put('customer_name', $customer->name);
        session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Welcome to FoodHub, ' . $customer->name . '! 🎉');
    }

    /**
     * Show simple login form - just phone or email
     */
    public function showLogin()
    {
        return view('customer.login');
    }

    /**
     * Simple login - just phone number or email, no password needed
     * If account exists → auto login
     * If not → redirect to register with pre-filled data
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone_or_email' => 'required|string|max:255',
        ]);

        $input = trim($request->phone_or_email);

        // Try to find customer by phone or email
        $customer = Customer::where('phone', $input)
            ->orWhere('email', $input)
            ->first();

        if (!$customer) {
            // Account doesn't exist - redirect to register
            $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);
            return redirect()->route('customer.register')
                ->with('info', 'No account found! Please register first.')
                ->with('prefill_email', $isEmail ? $input : '')
                ->with('prefill_phone', $isEmail ? '' : $input);
        }

        // Customer found - auto login with persistent session
        session()->put('customer_id', $customer->id);
        session()->put('customer_name', $customer->name);
        session()->regenerate();

        // Set long-lived session (30 days)
        session(['customer_remember' => true]);

        return redirect()->route('home')
            ->with('success', 'Welcome back, ' . $customer->name . '! 👋');
    }

    public function logout()
    {
        session()->forget('customer_id');
        session()->forget('customer_name');
        session()->forget('customer_remember');
        session()->invalidate();

        return redirect()->route('home')
            ->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        $customer = $this->getCurrentCustomer();
        if (!$customer) return redirect()->route('customer.login');

        $orders = $customer->orders()->latest()->limit(10)->get();
        $wishlist = $customer->wishlist()->get();

        return view('customer.profile', compact('customer', 'orders', 'wishlist'));
    }

    public function updateProfile(Request $request)
    {
        $customer = $this->getCurrentCustomer();
        if (!$customer) return redirect()->route('customer.login');

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->only(['name', 'phone', 'address']));
        session()->put('customer_name', $customer->name);

        return back()->with('success', 'Profile updated!');
    }

    /**
     * Admin: View all customer details
     */
    public function adminIndex()
    {
        $customers = Customer::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->get();

        $totalCustomers = Customer::count();
        $totalOrders = $customers->sum('orders_count');
        $totalRevenue = $customers->sum('orders_sum_total_amount');

        return view('admin.customers', compact(
            'customers', 'totalCustomers', 'totalOrders', 'totalRevenue'
        ));
    }

    /**
     * Admin: View single customer details
     */
    public function adminShow($id)
    {
        $customer = Customer::with(['orders' => function ($q) {
            $q->latest()->limit(20);
        }])->findOrFail($id);

        $totalSpent = $customer->orders->sum('total_amount');
        $totalOrders = $customer->orders()->count();

        return view('admin.customer-detail', compact('customer', 'totalSpent', 'totalOrders'));
    }

    /**
     * Admin: Delete customer
     */
    public function adminDelete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return back()->with('success', 'Customer deleted!');
    }

    protected function getCurrentCustomer()
    {
        $customerId = session('customer_id');
        if (!$customerId) return null;
        return Customer::find($customerId);
    }

    /**
     * Helper for checkout auto-fill
     */
    public function getCurrentCustomerForCheckout()
    {
        $customer = $this->getCurrentCustomer();
        return $customer ? $customer->phone : '';
    }

    public function getCurrentEmailForCheckout()
    {
        $customer = $this->getCurrentCustomer();
        return $customer ? $customer->email : '';
    }
}
