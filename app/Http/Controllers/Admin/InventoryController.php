<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $foods = Food::with('category')->orderBy('name')->get();

        $totalItems = $foods->count();
        $inStockCount = $foods->where('is_in_stock', true)->count();
        $outOfStockCount = $foods->where('is_in_stock', false)->count();
        $lowStockCount = $foods->filter(function ($food) {
            return $food->is_in_stock && $food->stock_quantity >= 0 && $food->stock_quantity <= $food->low_stock_threshold;
        })->count();

        return view('admin.inventory', compact(
            'foods', 'totalItems', 'inStockCount', 'outOfStockCount', 'lowStockCount'
        ));
    }

    public function updateStock(Request $request, $id)
    {
        $food = Food::findOrFail($id);
        $qty = (int) $request->input('stock_quantity', -1);

        $food->update([
            'stock_quantity' => $qty,
            'is_in_stock' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleInStock(Request $request, $id)
    {
        $food = Food::findOrFail($id);
        $food->update(['is_in_stock' => $request->input('is_in_stock', true)]);

        return response()->json(['success' => true]);
    }
}
