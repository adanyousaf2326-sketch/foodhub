<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food', function (Blueprint $table) {
            if (!Schema::hasColumn('food', 'prep_time')) {
                $table->integer('prep_time')->default(15)->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn('prep_time');
        });
    }
};
