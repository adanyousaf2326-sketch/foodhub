<?php

namespace App\Services;

class DeliveryCalculator
{
    // Restaurant location (Islamabad - Rawalpindi area)
    const RESTAURANT_LAT = 33.6844;
    const RESTAURANT_LNG = 73.0479;

    // Delivery charge tiers (km => charge in Rs)
    // Free delivery within 2 km, then per-km charges
    const FREE_DELIVERY_KM = 2;
    const BASE_DELIVERY_CHARGE = 50;     // Rs 50 for 2-4 km
    const PER_KM_CHARGE = 15;            // Rs 15 per km after 4 km
    const MAX_DELIVERY_KM = 25;          // Max delivery radius
    const AVG_SPEED_KMH = 25;            // Average speed in km/h (city traffic)
    const PREP_TIME_BUFFER = 10;         // Extra minutes for pickup

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    public static function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Calculate delivery charges based on distance
     */
    public static function calculateCharges(float $distanceKm): array
    {
        if ($distanceKm <= self::FREE_DELIVERY_KM) {
            return [
                'charges' => 0,
                'message' => 'Free delivery!',
                'is_free' => true,
            ];
        }

        $charge = self::BASE_DELIVERY_CHARGE;

        if ($distanceKm > 4) {
            $extraKm = $distanceKm - 4;
            $charge += ceil($extraKm) * self::PER_KM_CHARGE;
        }

        return [
            'charges' => $charge,
            'message' => 'Delivery charges: Rs. ' . number_format($charge),
            'is_free' => false,
        ];
    }

    /**
     * Calculate estimated delivery time in minutes
     */
    public static function calculateDeliveryTime(float $distanceKm, int $prepTime = 15): int
    {
        $travelTime = ceil(($distanceKm / self::AVG_SPEED_KMH) * 60); // minutes
        return $travelTime + $prepTime + self::PREP_TIME_BUFFER;
    }

    /**
     * Full calculation: distance, charges, time
     */
    public static function calculate(float $lat, float $lng, int $prepTime = 15): array
    {
        $distance = self::calculateDistance(
            self::RESTAURANT_LAT,
            self::RESTAURANT_LNG,
            $lat,
            $lng
        );

        $charges = self::calculateCharges($distance);
        $deliveryTime = self::calculateDeliveryTime($distance, $prepTime);

        $isWithinRadius = $distance <= self::MAX_DELIVERY_KM;

        return [
            'distance_km' => $distance,
            'delivery_charges' => $charges['charges'],
            'delivery_message' => $charges['message'],
            'is_free_delivery' => $charges['is_free'],
            'delivery_time_min' => $deliveryTime,
            'is_within_radius' => $isWithinRadius,
            'max_km' => self::MAX_DELIVERY_KM,
            'estimated_ready_min' => $prepTime,
        ];
    }

    /**
     * Get charge breakdown for display
     */
    public static function getChargeBreakdown(float $distanceKm): array
    {
        $lines = [];

        if ($distanceKm <= self::FREE_DELIVERY_KM) {
            $lines[] = "Within {$distanceKm} km — Free delivery!";
        } else {
            $lines[] = "Distance: {$distanceKm} km from restaurant";
            if ($distanceKm > 4) {
                $extraKm = ceil($distanceKm - 4);
                $lines[] = "Base charge (2-4 km): Rs. " . self::BASE_DELIVERY_CHARGE;
                $lines[] = "Extra {$extraKm} km × Rs. " . self::PER_KM_CHARGE . " = Rs. " . ($extraKm * self::PER_KM_CHARGE);
            } else {
                $lines[] = "Flat rate: Rs. " . self::BASE_DELIVERY_CHARGE;
            }
        }

        return $lines;
    }
}
