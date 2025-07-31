<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // === PELANGGAN & MOBIL ===
        Schema::create('pelanggan_mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('kontak')->nullable();
            $table->enum('jenis_pelanggan', ['perorangan', 'perusahaan'])->default('perorangan');
            $table->string('nama_perusahaan')->nullable();
            $table->string('merk_mobil');
            $table->string('tipe_mobil');
            $table->string('nopol')->unique();
            $table->year('tahun')->nullable();
            $table->string('warna')->nullable();
            $table->text('catatan_mobil')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nama_pelanggan', 'nopol', 'jenis_pelanggan']);
        });

        // === TRANSAKSI SERVICE ===
        Schema::create('transaksi_services', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->foreignId('pelanggan_mobil_id')->constrained()->onDelete('cascade');
            $table->string('kasir');
            $table->date('tanggal_service');
            $table->text('keluhan')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('pekerjaan_dilakukan')->nullable();
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'piutang'])->default('tunai');
            $table->enum('strategi_pembayaran', ['bayar_akhir', 'bayar_dimuka', 'cicilan'])->default('bayar_akhir');
            // $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar', 'lunas'])->default('belum_bayar');
            $table->enum('status_pembayaran', ['lunas', 'belum', 'sebagian'])->default('belum');
            $table->enum('status_pekerjaan', ['belum_dikerjakan', 'sedang_dikerjakan', 'selesai'])->default('belum_dikerjakan');
            $table->decimal('total_barang', 15, 2)->default(0);
            $table->decimal('total_jasa', 15, 2)->default(0);
            $table->decimal('total_keseluruhan', 15, 2)->default(0);
            $table->decimal('total_sudah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_pembayaran', 15, 2)->default(0);
            $table->date('jatuh_tempo')->nullable();
            $table->string('no_surat_pesanan')->nullable();
            $table->text('keterangan_piutang')->nullable();
            $table->text('keterangan_pembayaran')->nullable();
            $table->enum('status_service', ['dikerjakan', 'selesai', 'diambil'])->default('dikerjakan');
            $table->datetime('waktu_selesai')->nullable();
            $table->datetime('waktu_diambil')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tanggal_service', 'kasir', 'status_pembayaran', 'status_service', 'metode_pembayaran'], 'idx_service_status');
        });

        // === JASA SERVICE ===
        Schema::create('service_jasa_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_service_id')->constrained()->onDelete('cascade');
            $table->string('nama_jasa');
            $table->decimal('harga_jasa', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('transaksi_service_id');
        });

        // === BARANG SERVICE ===
        Schema::create('service_barang_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('pembelian_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->index('transaksi_service_id');
        });

        // === PEMBAYARAN (pengganti pembayaran_cicilan) ===
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_service_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer']);
            $table->string('keterangan')->nullable();
            $table->string('kasir');
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();
            $table->index(['transaksi_service_id', 'tanggal_bayar', 'kasir']);
        });

        // === Tambahan softDeletes untuk barangs dan pembelians ===
        Schema::table('barangs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_payments');
        Schema::dropIfExists('service_barang_items');
        Schema::dropIfExists('service_jasa_items');
        Schema::dropIfExists('transaksi_services');
        Schema::dropIfExists('pelanggan_mobils');

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
