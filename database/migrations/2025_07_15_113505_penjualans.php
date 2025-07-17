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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('kasir', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('tanggal');
            $table->index('kasir');
            $table->index(['tanggal', 'kasir']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};