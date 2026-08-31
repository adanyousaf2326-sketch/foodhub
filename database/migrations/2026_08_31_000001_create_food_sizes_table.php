<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained('food')->cascadeOnDelete();
            $table->string('name'); // e.g. Small, Medium, Large, Half, Full
            $table->decimal('price', 10, 2); // price for this size
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_sizes');
    }
};
