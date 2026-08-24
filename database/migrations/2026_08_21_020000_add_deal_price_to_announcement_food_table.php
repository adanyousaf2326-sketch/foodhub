<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcement_food', function (Blueprint $table) {
            $table->decimal('deal_price', 10, 2)
                ->nullable()
                ->after('food_id');
        });
    }

    public function down(): void
    {
        Schema::table('announcement_food', function (Blueprint $table) {
            $table->dropColumn('deal_price');
        });
    }
};
