<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiService;
use App\Models\ServiceBarangItem;
use App\Models\ServiceJasaItem;

class ServiceInvoiceController extends Controller
{
    public function show($id)
    {
        try {
            $transaksi = TransaksiService::with([
                'pelangganMobil',
                'serviceBarangItems.barang',
                'serviceBarangItems.pembelian',
                'serviceJasaItems'
            ])->findOrFail($id);

            return view('service.invoice', compact('transaksi'));
        } catch (\Exception $e) {
            return redirect()->route('service.transaksi')
                ->with('error', 'Invoice tidak ditemukan.');
        }
    }

    public function print($id)
    {
        try {
            $transaksi = TransaksiService::with([
                'pelangganMobil',
                'serviceBarangItems.barang',
                'serviceBarangItems.pembelian',
                'serviceJasaItems'
            ])->findOrFail($id);

            return view('service.invoice-print', compact('transaksi'));
        } catch (\Exception $e) {
            return redirect()->route('service.transaksi')
                ->with('error', 'Invoice tidak ditemukan.');
        }
    }
}