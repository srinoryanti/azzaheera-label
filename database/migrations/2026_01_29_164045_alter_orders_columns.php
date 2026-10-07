<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite: lewati ->change() agar migrate fresh tetap jalan.
        // Presisi decimal tidak kritis untuk dev lokal.
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->change();
            $table->decimal('discount', 15, 2)->default(0)->change();
            $table->decimal('tax', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });
    }

    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 8, 2)->change();
            $table->decimal('discount', 8, 2)->default(0)->change();
            $table->decimal('tax', 8, 2)->change();
            $table->decimal('total', 8, 2)->change();
        });
    }
};
