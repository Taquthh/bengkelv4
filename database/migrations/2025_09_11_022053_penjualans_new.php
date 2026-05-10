<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('penjualan_items', function (Blueprint $table) {
            $table->string('nama_barang_manual')->nullable()->after('barang_id');
        });
    }

    public function down(): void {
        Schema::table('penjualan_items', function (Blueprint $table) {
            $table->dropColumn('nama_barang_manual');
        });
    }
};
