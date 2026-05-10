<?php

use App\Livewire\BarangPembelian;
use App\Livewire\PengeluaranOperasionals;
use App\Livewire\TransaksiServices;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\BarangIndex;
use App\Livewire\DashboardKeuangan;
use App\Livewire\LaporanBulanan;
use App\Livewire\LaporanKeuangan;
use App\Livewire\LaporanMingguan;
use App\Livewire\LaporanOperasional;
use App\Livewire\LaporanPiutang;
use App\Livewire\LaporanSparepart;
use App\Livewire\PembelianIndex;
use App\Livewire\PenjualanCreate;
use App\Livewire\RiwayatService;
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
        Route::get('/transaksi-service', TransaksiServices::class)->name('transaksi.services');  
        Route::get('/riwayat-transaksi-barang', RiwayatTransaksiBarang::class)->name('riwayat.transaksi.barang');
        Route::get('/riwayat-service', RiwayatService::class)->name('riwayat.service');
        Route::get('/operasional', PengeluaranOperasionals::class)->name('pengeluaran.operasional');
        // // Opsional: daftar riwayat penjualan
        // Route::get('/penjualan', PenjualanIndex::class)->name('penjualan.index');
    });

    Route::get('/service/invoice/{id}', [App\Http\Controllers\ServiceInvoiceController::class, 'show'])
        ->name('service.invoice');
    Route::get('/service/invoice/{id}/print', [App\Http\Controllers\ServiceInvoiceController::class, 'print'])
        ->name('service.invoice.print');


    Route::middleware(['auth', 'role:keuangan,'])->group(function () {
        Route::get('/laporan-mingguan', LaporanMingguan::class)->name('laporan.mingguan');
        Route::get('/laporan-bulanan', LaporanBulanan::class)->name('laporan.bulanan');
        Route::get('/laporan-sparepart', LaporanSparepart::class)->name('laporan.keuangan');
        Route::get('/laporan-operasional', LaporanOperasional::class)->name('laporan.keuangan');
        Route::get('/laporan-piutang', LaporanPiutang::class)->name('laporan.keuangan');
    });

});


require __DIR__.'/auth.php';

