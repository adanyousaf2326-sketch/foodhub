<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', [
                'Delivery',
                'Dine In',
                'Takeaway'
            ])->default('Delivery')->after('customer_name');

            $table->foreignId('table_id')
                ->nullable()
                ->after('order_type')
                ->constrained('restaurant_tables')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropColumn(['order_type', 'table_id']);
        });
    }
};