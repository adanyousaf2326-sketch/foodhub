<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to support both MySQL and SQLite
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // Check if table already exists
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='customer_notifications'");
            if (empty($tables)) {
                DB::statement('CREATE TABLE customer_notifications (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    customer_id INTEGER,
                    title TEXT NOT NULL,
                    message TEXT NOT NULL,
                    type TEXT DEFAULT "deal",
                    data TEXT,
                    is_read INTEGER DEFAULT 0,
                    read_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )');
                DB::statement('CREATE INDEX idx_cn_customer ON customer_notifications(customer_id)');
                DB::statement('CREATE INDEX idx_cn_unread ON customer_notifications(customer_id, is_read)');
            }
        } else {
            Schema::create('customer_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('message');
                $table->string('type')->default('deal');
                $table->json('data')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                $table->index(['customer_id', 'is_read']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
    }
};
