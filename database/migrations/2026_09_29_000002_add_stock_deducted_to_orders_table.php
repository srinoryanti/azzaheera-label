<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Penanda apakah stok produk sudah dikurangi untuk order ini.
            // Mencegah pengembalian stok ganda & melindungi order lama
            // (yang dibuat sebelum fitur pengurangan stok ada).
            $table->boolean('stock_deducted')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stock_deducted');
        });
    }
};
