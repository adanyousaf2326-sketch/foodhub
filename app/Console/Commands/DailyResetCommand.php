<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rider;
use App\Models\Order;

class DailyResetCommand extends Command
{
    protected $signature = 'daily:reset';
    protected $description = 'Daily reset: turn off all riders, clear pending order assignments';

    public function handle()
    {
        // Auto-create columns if missing (SQLite)
        $this->ensureFoodColumns();

        // 1. 🛵 Turn off all riders
        $riders = Rider::where('is_on_duty', true)->update(['is_on_duty' => false]);
        $this->info("🛵 {$riders} riders turned OFF");

        // 2. 📋 Reset assigned orders → Pending
        $assigned = Order::where('status', 'Assigned')
            ->whereNull('picked_up_at')
            ->update(['status' => 'Pending', 'rider_id' => null]);
        $this->info("📋 {$assigned} assigned orders → Pending");

        // 3. 💰 Cash Pending → Delivered
        $cash = Order::where('status', 'Cash Pending')
            ->update(['status' => 'Delivered']);
        $this->info("💰 {$cash} cash pending → Delivered");

        // 4. ✅ Stuck orders → Delivered
        $stuck = Order::whereIn('status', ['Picked Up', 'Out for Delivery'])
            ->update(['status' => 'Delivered']);
        $this->info("✅ {$stuck} stuck orders → Delivered");

        // 5. 🍕 Re-enable all food items
        \App\Models\Food::where('is_in_stock', false)->update(['is_in_stock' => true]);
        try {
            \App\Models\Food::query()->update(['available_at' => null]);
        } catch (\Exception $e) { /* column may not exist */ }
        $this->info("🍕 Food items re-enabled");

        // 6. 🪑 Free occupied tables
        try {
            $tables = \App\Models\RestaurantTable::where('status', 'occupied')
                ->update(['status' => 'available']);
            $this->info("🪑 {$tables} tables freed");
        } catch (\Exception $e) { /* table may not exist */ }

        // 7. 🗑️ Clear caches
        \Cache::forget('home_foods');
        \Cache::forget('home_categories');
        \Cache::forget('home_announcements');
        $this->info("🗑️ Caches cleared");

        $this->info("🔄 Daily reset completed at " . now()->format('Y-m-d h:i A'));
        return Command::SUCCESS;
    }

    protected function ensureFoodColumns()
    {
        $columns = \DB::select('PRAGMA table_info(food)');
        $columnNames = array_column($columns, 'name');
        if (!in_array('is_in_stock', $columnNames)) {
            \DB::statement('ALTER TABLE food ADD COLUMN stock_quantity INTEGER NOT NULL DEFAULT -1');
            \DB::statement('ALTER TABLE food ADD COLUMN is_in_stock BOOLEAN NOT NULL DEFAULT 1');
            \DB::statement('ALTER TABLE food ADD COLUMN low_stock_threshold INTEGER NOT NULL DEFAULT 5');
            \DB::statement('ALTER TABLE food ADD COLUMN available_at TIMESTAMP NULL DEFAULT NULL');
        }
    }
}
