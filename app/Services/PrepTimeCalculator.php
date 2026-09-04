<?php

namespace App\Services;

use App\Models\Order;

class PrepTimeCalculator
{
    /**
     * Calculate realistic prep time for an order.
     *
     * Logic:
     * - First 3 items: full prep time each (kitchen has 3 stations)
     * - Items 4-10: 70% of prep time (partial parallelism)
     * - Items 11+: 50% of prep time (bottleneck stations)
     * - Different item types need separate prep (sequential)
     * - Same item type: batches of 3 can be made together
     */
    public static function calculate(Order $order): int
    {
        $items = $order->items;
        if ($items->isEmpty()) return 10;

        $totalMinutes = 0;
        $stationTime = [0, 0, 0]; // Track 3 kitchen stations

        foreach ($items as $item) {
            $food = $item->food;
            $basePrepTime = $food ? ($food->prep_time ?? 15) : 15;
            $quantity = max(1, $item->quantity);

            // For same item type, batches of 3 share prep time
            $batches = ceil($quantity / 3);
            $batchTime = $basePrepTime + (($batches - 1) * ($basePrepTime * 0.4)); // each extra batch adds 40% time

            // Assign to least busy station (greedy)
            $minStation = array_search(min($stationTime), $stationTime);
            $stationTime[$minStation] += $batchTime;
        }

        $totalMinutes = max($stationTime);
        $totalMinutes = max($totalMinutes, 5); // minimum 5 min
        $totalMinutes = min($totalMinutes, 120); // max 2 hours

        return (int) ceil($totalMinutes);
    }

    /**
     * Quick estimate for checkout (without full order model)
     */
    public static function estimateFromCart(array $cart): int
    {
        $stationTime = [0, 0, 0]; // 3 kitchen stations

        foreach ($cart as $item) {
            $quantity = max(1, $item['quantity'] ?? 1);

            // Find food prep time
            $basePrepTime = 15; // default
            if (!empty($item['food_id'])) {
                $food = \App\Models\Food::find($item['food_id']);
                if ($food) {
                    $basePrepTime = $food->prep_time ?? 15;
                }
            }

            $batches = ceil($quantity / 3);
            $batchTime = $basePrepTime + (($batches - 1) * ($basePrepTime * 0.4));

            $minStation = array_search(min($stationTime), $stationTime);
            $stationTime[$minStation] += $batchTime;
        }

        return (int) ceil(max($stationTime));
    }
}
