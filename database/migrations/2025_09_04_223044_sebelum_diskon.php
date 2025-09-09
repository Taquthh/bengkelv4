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
            $table->decimal('subtotal_sebelum_diskon', 15, 2)->default(0)->after('total_jasa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_services', function (Blueprint $table) {
            $table->dropColumn('subtotal_sebelum_diskon');
        });
    }
};
