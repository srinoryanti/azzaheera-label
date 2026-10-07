<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL-only. Di SQLite enum = TEXT, nilai 'transfer' langsung bisa dipakai.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement("ALTER TABLE transactions MODIFY COLUMN mode ENUM('cod','card','paypal','transfer') NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement("ALTER TABLE transactions MODIFY COLUMN mode ENUM('cod','card','paypal') NOT NULL");
    }
};
