<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Food;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('foods')->latest()->get();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $foods = Food::where('is_available', true)->orderBy('name')->get();

        return view('admin.announcements.create', compact('foods'));
    }

    public function store(Request $request)
    {
        $announcement = $this->saveAnnouncement($request);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement published successfully!');
    }

    public function edit(Announcement $announcement)
    {
        $foods = Food::where('is_available', true)->orderBy('name')->get();
        $announcement->load('foods');

        return view('admin.announcements.edit', compact('announcement', 'foods'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->saveAnnouncement($request, $announcement);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    private function saveAnnouncement(Request $request, ?Announcement $announcement = null): Announcement
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'button_text' => 'nullable|string|max:80',
            'deal_total' => 'nullable|numeric|min:0',
            'deal_image' => 'nullable|url|max:2048',
            'food_ids' => 'nullable|array',
            'food_ids.*' => 'exists:food,id',
            'deal_prices' => 'nullable|array',
            'deal_prices.*' => 'nullable|numeric|min:0',
            'food_quantities' => 'nullable|array',
            'food_quantities.*' => 'nullable|integer|min:1|max:99',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $announcement ??= new Announcement();
        $announcement->fill([
            'title' => $validated['title'],
            'message' => $validated['message'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'deal_total' => $validated['deal_total'] ?? null,
            'deal_image' => $validated['deal_image'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);
        $announcement->save();
        $foodSync = collect($validated['food_ids'] ?? [])
            ->mapWithKeys(function ($foodId) use ($validated) {
                return [
                    $foodId => [
                        'deal_price' => $validated['deal_prices'][$foodId] ?? null,
                        'quantity' => $validated['food_quantities'][$foodId] ?? 1,
                    ],
                ];
            })
            ->all();

        $announcement->foods()->sync($foodSync);

        return $announcement;
    }
}
