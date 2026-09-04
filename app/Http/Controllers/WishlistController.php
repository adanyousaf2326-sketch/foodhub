<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Food;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected function getCustomer()
    {
        $customerId = session('customer_id');
        if (!$customerId) return null;
        return Customer::find($customerId);
    }

    public function index()
    {
        $customer = $this->getCustomer();
        if (!$customer) return response()->json(['error' => 'Login required'], 401);

        $wishlist = $customer->wishlist()->get();
        return response()->json(['wishlist' => $wishlist]);
    }

    public function toggle($foodId)
    {
        $customer = $this->getCustomer();
        if (!$customer) return response()->json(['error' => 'Login required'], 401);

        $food = Food::findOrFail($foodId);

        if ($customer->wishlist()->where('food_id', $foodId)->exists()) {
            $customer->wishlist()->detach($foodId);
            return response()->json(['success' => true, 'action' => 'removed', 'message' => 'Removed from favorites']);
        }

        $customer->wishlist()->attach($foodId);
        return response()->json(['success' => true, 'action' => 'added', 'message' => 'Added to favorites ❤️']);
    }

    public function destroy($foodId)
    {
        $customer = $this->getCustomer();
        if (!$customer) return response()->json(['error' => 'Login required'], 401);

        $customer->wishlist()->detach($foodId);
        return response()->json(['success' => true, 'message' => 'Removed from favorites']);
    }
}
