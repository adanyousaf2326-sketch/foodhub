<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\FoodVariation;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with(['category', 'variations'])->latest()->get();
        return view('admin.food.index', compact('foods'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.food.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $hasVariations = $request->boolean('has_variations');

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => $hasVariations ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|url|max:2048',
            'is_available' => 'nullable|boolean',
            'variations' => $hasVariations ? 'required|array|min:1' : 'nullable|array',
            'variations.*.name' => 'required_with:variations|string|max:100',
            'variations.*.price' => 'required_with:variations|numeric|min:0',
            'variations.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $basePrice = $request->filled('price') ? (float) $request->price : 0;

        // If variations are provided and base price wasn't specified, use the first/min variation price
        if ($hasVariations && $request->filled('variations')) {
            $validVariations = array_filter($request->variations, fn($v) => !empty($v['name']) && isset($v['price']));
            if (!empty($validVariations) && !$request->filled('price')) {
                $basePrice = (float) min(array_column($validVariations, 'price'));
            }
        }

        $food = Food::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $basePrice,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'image' => $request->image,
            'is_available' => $request->has('is_available'),
        ]);

        if ($hasVariations && $request->filled('variations')) {
            foreach ($request->variations as $varData) {
                if (!empty($varData['name']) && isset($varData['price']) && $varData['price'] !== '') {
                    $food->variations()->create([
                        'name' => trim($varData['name']),
                        'price' => (float) $varData['price'],
                        'discount_percentage' => !empty($varData['discount_percentage']) ? (float) $varData['discount_percentage'] : 0,
                        'is_available' => true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.food.index')->with('success', 'Food item added successfully!');
    }

    public function show(Food $food)
    {
        $food->load(['category', 'variations']);
        return view('admin.food.show', compact('food'));
    }

    public function edit(Food $food)
    {
        $food->load('variations');
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.food.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food)
    {
        $hasVariations = $request->boolean('has_variations');

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => $hasVariations ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
            'variations' => $hasVariations ? 'required|array|min:1' : 'nullable|array',
            'variations.*.name' => 'required_with:variations|string|max:100',
            'variations.*.price' => 'required_with:variations|numeric|min:0',
            'variations.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $basePrice = $request->filled('price') ? (float) $request->price : (float) $food->price;

        if ($hasVariations && $request->filled('variations')) {
            $validVariations = array_filter($request->variations, fn($v) => !empty($v['name']) && isset($v['price']));
            if (!empty($validVariations) && !$request->filled('price')) {
                $basePrice = (float) min(array_column($validVariations, 'price'));
            }
        }

        $food->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $basePrice,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'image' => $request->image,
            'is_available' => $request->has('is_available'),
        ]);

        // Sync variations
        if ($hasVariations && $request->filled('variations')) {
            $food->variations()->delete();
            foreach ($request->variations as $varData) {
                if (!empty($varData['name']) && isset($varData['price']) && $varData['price'] !== '') {
                    $food->variations()->create([
                        'name' => trim($varData['name']),
                        'price' => (float) $varData['price'],
                        'discount_percentage' => !empty($varData['discount_percentage']) ? (float) $varData['discount_percentage'] : 0,
                        'is_available' => true,
                    ]);
                }
            }
        } else {
            // Remove variations if unchecked
            $food->variations()->delete();
        }

        return redirect()->route('admin.food.index')->with('success', 'Food updated successfully!');
    }

    public function destroy(Food $food)
    {
        $food->variations()->delete();
        $food->delete();
        return redirect()->route('admin.food.index')->with('success', 'Food deleted successfully!');
    }
}
