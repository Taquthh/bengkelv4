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
        Schema::table('transaksi_services', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_services', 'diskon')) {
                $table->decimal('diskon', 15, 2)->default(0)->after('total_jasa');
            }

            if (!Schema::hasColumn('transaksi_services', 'tipe_diskon')) {
                $table->enum('tipe_diskon', ['nominal', 'persentase'])->default('nominal')->after('diskon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_services', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_services', 'diskon')) {
                $table->dropColumn('diskon');
            }
            if (Schema::hasColumn('transaksi_services', 'tipe_diskon')) {
                $table->dropColumn('tipe_diskon');
            }
        });
    }
};
