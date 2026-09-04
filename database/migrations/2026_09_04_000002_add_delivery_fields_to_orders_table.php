<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_charges')) {
                $table->decimal('delivery_charges', 10, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'delivery_distance_km')) {
                $table->decimal('delivery_distance_km', 8, 2)->nullable()->after('delivery_charges');
            }
            if (!Schema::hasColumn('orders', 'delivery_time_min')) {
                $table->integer('delivery_time_min')->nullable()->after('delivery_distance_km');
            }
            if (!Schema::hasColumn('orders', 'customer_lat')) {
                $table->decimal('customer_lat', 10, 7)->nullable()->after('delivery_time_min');
            }
            if (!Schema::hasColumn('orders', 'customer_lng')) {
                $table->decimal('customer_lng', 10, 7)->nullable()->after('customer_lat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_charges', 'delivery_distance_km', 'delivery_time_min', 'customer_lat', 'customer_lng']);
        });
    }
};
