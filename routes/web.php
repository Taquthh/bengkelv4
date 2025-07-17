<?php

use App\Livewire\BarangPembelian;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\BarangIndex;
use App\Livewire\PembelianIndex;
use App\Livewire\PenjualanCreate;
use App\Livewire\RiwayatTransaksi;
use App\Livewire\RiwayatTransaksiBarang;
use App\Livewire\TransaksiBarang;

Route::get('/', fn () => view('welcome'));

Route::get('/uji-403', function () {
    abort(403);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['auth', 'role:kasir,owner'])->group(function () {
        Route::get('/barang/stok', PembelianIndex::class); // Ini hanya untuk Livewire v3
        Route::get('/barang', BarangIndex::class); // Ini hanya untuk Livewire v3
        Route::get('/transaksi-barang', TransaksiBarang::class)->name('transaksi.barang'); 
        Route::get('/riwayat-transaksi-barang', RiwayatTransaksiBarang::class)->name('riwayat.transaksi.barang');

        // // Opsional: daftar riwayat penjualan
        // Route::get('/penjualan', PenjualanIndex::class)->name('penjualan.index');
    });


    Route::middleware(['auth', 'role:kasir'])->group(function () {
        Route::get('/kasir', fn () => view('kasir.index'))->name('kasir.index');
    });

    Route::middleware(['role:keuangan'])->group(function () {
        Route::get('/keuangan', fn () => view('keuangan.index'))->name('keuangan.index');
    });

    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner', fn () => view('owner.index'))->name('owner.index');
    });
});


require __DIR__.'/auth.php';

