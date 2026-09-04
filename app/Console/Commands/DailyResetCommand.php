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
        // 1. Turn off all riders
        $ridersUpdated = Rider::where('is_on_duty', true)->update(['is_on_duty' => false]);
        $this->info("✅ Turned off {$ridersUpdated} riders.");

        // 2. Reset un-acted orders (Pending/Assigned with no pickup) back to Pending
        $ordersReset = Order::whereIn('status', ['Assigned'])
            ->whereNull('picked_up_at')
            ->update(['status' => 'Pending', 'rider_id' => null]);
        $this->info("✅ Reset {$ordersReset} assigned orders back to Pending.");

        // 3. Log the reset
        $this->info("🔄 Daily reset completed at " . now()->format('Y-m-d h:i A'));

        return Command::SUCCESS;
    }
}
