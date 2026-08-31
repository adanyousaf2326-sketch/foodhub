<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\FoodSize;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with(['category', 'foodSizes'])->latest()->get();

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
            'size_names' => 'nullable|array',
            'size_names.*' => 'nullable|string|max:255',
            'size_prices' => 'nullable|array',
            'size_prices.*' => 'nullable|numeric|min:0',
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

        // Save sizes
        if ($request->filled('size_names')) {
            foreach ($request->size_names as $index => $name) {
                if (!empty($name) && isset($request->size_prices[$index])) {
                    $food->foodSizes()->create([
                        'name' => $name,
                        'price' => $request->size_prices[$index],
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.food.index')
            ->with('success', 'Food item added successfully!');
    }

    public function show(Food $food)
    {
        $food->load(['category', 'foodSizes']);

        return view('admin.food.show', compact('food'));
    }

    public function edit(Food $food)
    {
        $food->load('foodSizes');
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
            'size_names' => 'nullable|array',
            'size_names.*' => 'nullable|string|max:255',
            'size_prices' => 'nullable|array',
            'size_prices.*' => 'nullable|numeric|min:0',
        ]);

        $validated['is_available'] = $request->has('is_available');

        $food->update($validated);

        // Sync sizes
        $food->foodSizes()->delete();
        if ($request->filled('size_names')) {
            foreach ($request->size_names as $index => $name) {
                if (!empty($name) && isset($request->size_prices[$index])) {
                    $food->foodSizes()->create([
                        'name' => $name,
                        'price' => $request->size_prices[$index],
                    ]);
                }
            }
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