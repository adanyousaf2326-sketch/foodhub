<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'nullable|string',
        'image' => 'nullable|string|max:1000',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    // If no image URL provided, generate one based on the category name
    if (empty($validated['image'])) {
        $keyword = urlencode($validated['name']);

        $validated['image'] =
            "https://loremflickr.com/600/400/" . $keyword;
    }

    Category::create($validated);

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Category added successfully!');
}

    public function edit(Category $category)
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}