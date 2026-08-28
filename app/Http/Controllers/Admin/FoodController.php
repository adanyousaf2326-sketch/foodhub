<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with('category')->latest()->get();

        return view('admin.food.index', compact('foods'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.food.create', compact('categories'));
    }

   public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'discount_percentage' => 'nullable|numeric|min:0|max:100',
        'image' => 'nullable|url|max:2048',
        'is_available' => 'nullable|boolean',
    ]);

    $food = Food::create([
        'category_id' => $request->category_id,
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'discount_percentage' => $request->discount_percentage ?? 0,
        'image' => $request->image,
        'is_available' => $request->has('is_available'),
    ]);

    // Save inventory if tracking is enabled
    if ($request->has('track_stock')) {
        \App\Models\Inventory::updateOrCreate(
            ['food_id' => $food->id],
            [
                'stock_quantity' => (int) $request->input('stock_quantity', 0),
                'low_stock_threshold' => (int) $request->input('low_stock_threshold', 5),
                'track_stock' => true,
            ]
        );
    }

    return redirect()
        ->route('admin.food.index')
        ->with('success', 'Food item added successfully!');
}
    public function show(Food $food)
    {
        $food->load('category');

        return view('admin.food.show', compact('food'));
    }

    public function edit(Food $food)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.food.edit',
            compact('food', 'categories')
        );
    }

    public function update(Request $request, Food $food)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');

        $food->update($validated);

        // Update inventory
        if ($request->has('track_stock')) {
            \App\Models\Inventory::updateOrCreate(
                ['food_id' => $food->id],
                [
                    'stock_quantity' => (int) $request->input('stock_quantity', 0),
                    'low_stock_threshold' => (int) $request->input('low_stock_threshold', 5),
                    'track_stock' => true,
                ]
            );
        } else {
            \App\Models\Inventory::where('food_id', $food->id)->update(['track_stock' => false]);
        }

        return redirect()
            ->route('admin.food.index')
            ->with('success', 'Food updated successfully!');
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return redirect()
            ->route('admin.food.index')
            ->with('success', 'Food deleted successfully!');
    }
}