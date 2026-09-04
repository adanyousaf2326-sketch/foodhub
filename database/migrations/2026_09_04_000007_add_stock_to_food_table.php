<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->integer('stock_quantity')->default(-1)->after('prep_time'); // -1 = unlimited
            $table->boolean('is_in_stock')->default(true)->after('stock_quantity');
            $table->integer('low_stock_threshold')->default(5)->after('is_in_stock');
        });
    }

    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'is_in_stock', 'low_stock_threshold']);
        });
    }
};
