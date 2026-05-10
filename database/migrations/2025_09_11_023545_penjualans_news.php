<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penjualan_items', function (Blueprint $table) {
            // Existing columns remain, add new columns for manual items
            $table->string('satuan', 20)->default('pcs')->after('jumlah');
            $table->text('keterangan')->nullable()->after('harga_beli_manual');
            $table->boolean('is_manual')->default(false)->after('keterangan');
            
            // Modify existing foreign key constraints to allow null for manual items
            $table->unsignedBigInteger('barang_id')->nullable()->change();
            $table->unsignedBigInteger('pembelian_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_items', function (Blueprint $table) {
            $table->dropColumn([
                'nama_barang_manual',
                'harga_beli_manual', 
                'satuan',
                'keterangan',
                'is_manual'
            ]);
            
            // Restore original foreign key constraints (if needed)
            $table->unsignedBigInteger('barang_id')->nullable(false)->change();
            $table->unsignedBigInteger('pembelian_id')->nullable(false)->change();
        });
    }
};