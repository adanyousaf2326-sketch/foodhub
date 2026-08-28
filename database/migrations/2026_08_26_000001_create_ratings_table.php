<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('stars')->default(5); // 1-5
            $table->text('review')->nullable();
            $table->string('customer_name')->nullable();
            $table->timestamps();

            $table->unique('order_id'); // One rating per order
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
