<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL-only: SQLite menyimpan enum sebagai TEXT dan tidak perlu MODIFY.
        // Guard ini membuat migrate bisa jalan di VSCode maupun Clopen
        // tanpa MySQL (mode SQLite) dan tanpa validasi ke layanan luar.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status ENUM(
                'pending_payment',
                'ordered',
                'waiting_verification',
                'processing',
                'shipped',
                'delivered',
                'canceled'
            )
            NOT NULL
            DEFAULT 'waiting_verification'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status ENUM(
                'pending_payment',
                'ordered',
                'delivered',
                'canceled'
            )
            NOT NULL
            DEFAULT 'pending_payment'
        ");
    }
};