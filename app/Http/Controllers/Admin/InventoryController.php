<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InventoryController extends Controller
{
    public function index()
    {
        $this->ensureColumnsExist();

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

        $data = ['is_in_stock' => $request->input('is_in_stock', true)];

        // If disabling, set available_at time
        if (!$request->input('is_in_stock', true)) {
            $minutes = $request->input('available_in_minutes');
            if ($minutes && $minutes > 0) {
                $data['available_at'] = now()->addMinutes((int) $minutes);
            } elseif ($request->input('available_at')) {
                $data['available_at'] = $request->input('available_at');
            } else {
                $data['available_at'] = null;
            }
        } else {
            $data['available_at'] = null;
        }

        $food->update($data);

        return response()->json(['success' => true]);
    }

    /**
     * Ensure stock columns exist in the food table
     */
    protected function ensureColumnsExist()
    {
        $table = 'food';
        $columns = DB::select("PRAGMA table_info({$table})");
        $columnNames = array_column($columns, 'name');

        if (!in_array('stock_quantity', $columnNames)) {
            DB::statement("ALTER TABLE {$table} ADD COLUMN stock_quantity INTEGER NOT NULL DEFAULT -1");
        }
        if (!in_array('is_in_stock', $columnNames)) {
            DB::statement("ALTER TABLE {$table} ADD COLUMN is_in_stock BOOLEAN NOT NULL DEFAULT 1");
        }
        if (!in_array('low_stock_threshold', $columnNames)) {
            DB::statement("ALTER TABLE {$table} ADD COLUMN low_stock_threshold INTEGER NOT NULL DEFAULT 5");
        }
        if (!in_array('available_at', $columnNames)) {
            DB::statement("ALTER TABLE {$table} ADD COLUMN available_at TIMESTAMP NULL DEFAULT NULL");
        }
    }
}
