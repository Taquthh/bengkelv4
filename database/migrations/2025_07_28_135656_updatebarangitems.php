<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah tabel ada
        if (!Schema::hasTable('service_barang_items')) {
            throw new Exception('Tabel service_barang_items tidak ditemukan!');
        }

        Schema::table('service_barang_items', function (Blueprint $table) {
            // Ubah kolom yang sudah ada menjadi nullable
            $table->unsignedBigInteger('barang_id')->nullable()->change();
            $table->unsignedBigInteger('pembelian_id')->nullable()->change();
        });

        // Tambah kolom baru jika belum ada
        Schema::table('service_barang_items', function (Blueprint $table) {
            if (!Schema::hasColumn('service_barang_items', 'nama_barang_manual')) {
                $table->string('nama_barang_manual')->nullable();
            }
            
            if (!Schema::hasColumn('service_barang_items', 'satuan')) {
                $table->string('satuan', 50)->nullable();
            }
            
            if (!Schema::hasColumn('service_barang_items', 'is_manual')) {
                $table->boolean('is_manual')->default(false);
            }
        });

        // Verifikasi perubahan
        echo "Verifying table structure...\n";
        $columns = DB::select("DESCRIBE service_barang_items");
        foreach ($columns as $column) {
            echo "- {$column->Field}: {$column->Type}, NULL: {$column->Null}\n";
        }
    }

    public function down()
    {
        Schema::table('service_barang_items', function (Blueprint $table) {
            $table->dropColumn([
                'nama_barang_manual',
                'satuan',
                'is_manual',
                'status_barang', 
                'keterangan'
            ]);
        });
    }
};
