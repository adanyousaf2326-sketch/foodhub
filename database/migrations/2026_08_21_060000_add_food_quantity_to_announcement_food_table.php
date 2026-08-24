<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcement_food', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1)->after('deal_price');
        });
    }

    public function down(): void
    {
        Schema::table('announcement_food', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
