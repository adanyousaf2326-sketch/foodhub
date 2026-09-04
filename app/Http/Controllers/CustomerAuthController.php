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
        // Check if already registered
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

        // Auto login
        session()->put('customer_id', $customer->id);
        session()->put('customer_name', $customer->name);
        session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Welcome to FoodHub, ' . $customer->name . '! 🎉');
    }

    public function showLogin()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.']);
        }

        session()->put('customer_id', $customer->id);
        session()->put('customer_name', $customer->name);
        session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Welcome back, ' . $customer->name . '! 👋');
    }

    public function logout()
    {
        session()->forget('customer_id');
        session()->forget('customer_name');
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

    protected function getCurrentCustomer()
    {
        $customerId = session('customer_id');
        if (!$customerId) return null;
        return Customer::find($customerId);
    }
}
