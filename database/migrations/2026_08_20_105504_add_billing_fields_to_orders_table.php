<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)
                ->nullable()
                ->after('total_amount');

            $table->decimal('change_amount', 10, 2)
                ->nullable()
                ->after('paid_amount');

            $table->timestamp('paid_at')
                ->nullable()
                ->after('change_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paid_amount',
                'change_amount',
                'paid_at',
            ]);
        });
    }
};