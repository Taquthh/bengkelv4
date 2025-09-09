<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiService;
use App\Models\ServiceBarangItem;
use App\Models\ServiceJasaItem;
use App\Models\ServicePayment;
use App\Models\PengeluaranOperasional;
use App\Models\PenjualanItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]    

class LaporanMingguan extends Component
{
    public $weekStart;
    public $weekEnd;
    public $weekNumber;
    public $activeTab = 'pendapatan';
    public $isCurrentWeek = false;
    public $canGoToPrevious = true;
    public $canGoToNext = false;

    public function mount()
    {
        $this->initializeCurrentWeek();
    }

    private function initializeCurrentWeek()
    {
        $now = Carbon::now();
        $this->weekStart = $now->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->weekEnd = $now->endOfWeek(Carbon::SUNDAY)->toDateString();
        $this->weekNumber = $now->weekOfYear;
        $this->isCurrentWeek = true;
        $this->canGoToNext = false;
    }

    public function previousWeek()
    {
        $currentStart = Carbon::parse($this->weekStart);
        $newStart = $currentStart->subWeek();
        
        $this->weekStart = $newStart->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->weekEnd = $newStart->endOfWeek(Carbon::SUNDAY)->toDateString();
        $this->weekNumber = $newStart->weekOfYear;
        $this->isCurrentWeek = $newStart->isSameWeek(Carbon::now());
        
        $this->canGoToNext = true;
        $this->canGoToPrevious = $newStart->greaterThan(Carbon::now()->startOfYear());
    }

    public function nextWeek()
    {
        if (!$this->canGoToNext) return;
        
        $currentStart = Carbon::parse($this->weekStart);
        $newStart = $currentStart->addWeek();
        
        $this->weekStart = $newStart->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->weekEnd = $newStart->endOfWeek(Carbon::SUNDAY)->toDateString();
        $this->weekNumber = $newStart->weekOfYear;
        $this->isCurrentWeek = $newStart->isSameWeek(Carbon::now());
        
        $this->canGoToNext = !$newStart->isSameWeek(Carbon::now());
        $this->canGoToPrevious = true;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getWeeklyDataProperty()
    {
        return $this->getServiceTransactions();
    }

    public function getSummaryProperty()
    {
        $weeklyData = $this->getServiceTransactions();
        $operationalExpenses = $this->getOperationalDataProperty();
        
        $totalPendapatanBruto = $weeklyData->sum('jumlah');
        $totalDiscount = $weeklyData->sum('discount');
        $totalModal = $weeklyData->sum('modal');
        $totalJasa = $weeklyData->sum('jasa');
        $totalLabaSpart = $weeklyData->sum('laba_spart');
        $totalPiutang = $this->getPiutangData();
        $totalOperasional = $operationalExpenses->sum('jumlah');
        $totalDp = $this->getDpData();

        $pendapatanBersih = $totalPendapatanBruto - $totalModal - $totalDiscount - $totalOperasional;

        return [
            'total_pendapatan_bruto' => $totalPendapatanBruto,
            'total_discount' => $totalDiscount,
            'total_modal' => $totalModal,
            'total_jasa' => $totalJasa,
            'total_laba_spart' => $totalLabaSpart,
            'total_piutang' => $totalPiutang,
            'total_dp' => $totalDp,
            'operasional' => $totalOperasional,
            'pendapatan_bersih' => $pendapatanBersih,
        ];
    }

    public function getOperationalDataProperty()
    {
        return PengeluaranOperasional::whereBetween('tanggal', [$this->weekStart, $this->weekEnd])
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal)->format('d/m'),
                    'kategori' => 'Operasional',
                    'deskripsi' => $item->nama_item,
                    'jumlah' => $item->jumlah_pengeluaran,
                ];
            });
    }

    public function getOperationalSummaryProperty()
    {
        $operationalData = $this->operationalData;
        
        return [
            'total_operasional' => $operationalData->sum('jumlah'),
            'total_items' => $operationalData->count(),
        ];
    }

    public function getSparepartDataProperty()
    {
        // Data dari ServiceBarangItem (Transaksi Service)
        $serviceItems = ServiceBarangItem::whereHas('transaksiService', function ($query) {
            $query->whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd]);
        })
        ->with(['transaksiService', 'barang', 'pembelian'])
        ->get()
        ->map(function ($item) {
            $hargaBeli = $item->is_manual ? $item->harga_beli_manual : ($item->pembelian ? $item->pembelian->harga_beli : 0);
            $totalModal = $hargaBeli * $item->jumlah;
            
            return [
                'tanggal' => Carbon::parse($item->transaksiService->tanggal_service)->format('d/m'),
                'item' => $item->is_manual ? $item->nama_barang_manual : $item->barang->nama,
                'jumlah' => $totalModal,
                'source' => 'service'
            ];
        });

        // Data dari PenjualanItem (Penjualan Sparepart)
        $penjualanItems = PenjualanItem::whereHas('penjualan', function ($query) {
            $query->whereBetween('tanggal', [$this->weekStart, $this->weekEnd]);
        })
        ->with(['penjualan', 'barang', 'pembelian'])
        ->get()
        ->map(function ($item) {
            $hargaBeli = $item->pembelian ? $item->pembelian->harga_beli : 0;
            $totalModal = $hargaBeli * $item->jumlah;
            
            return [
                'tanggal' => Carbon::parse($item->penjualan->tanggal)->format('d/m'),
                'item' => $item->barang ? $item->barang->nama : 'Item tidak ditemukan',
                'jumlah' => $totalModal,
                'source' => 'penjualan'
            ];
        });

        // Gabungkan dan urutkan berdasarkan tanggal
        return $serviceItems->concat($penjualanItems)
            ->sortBy('tanggal')
            ->values();
    }

    public function getSparepartSummaryProperty()
    {
        $sparepartData = $this->getSparepartDataProperty();
        
        return [
            'total_pengeluaran' => $sparepartData->sum('jumlah'),
            'total_items' => $sparepartData->count(),
        ];
    }

private function getServiceTransactions()
{
    $transaksiMingguIni = TransaksiService::whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd])
        ->with(['pelangganMobil', 'serviceBarangItems.barang', 'serviceBarangItems.pembelian', 'serviceJasaItems'])
        ->orderBy('tanggal_service')
        ->get();

    return $transaksiMingguIni->values()->map(function ($transaksi, $index) {
        // Hitung modal barang (hanya item valid)
        $totalModal = $transaksi->serviceBarangItems
            ->filter(function ($item) {
                // pastikan ada nama barang manual atau relasi barang
                return $item->is_manual
                    ? !empty($item->nama_barang_manual)
                    : !empty($item->barang);
            })
            ->sum(function ($item) {
                if ($item->is_manual) {
                    return $item->harga_beli_manual * $item->jumlah;
                }
                return $item->pembelian ? ($item->pembelian->harga_beli * $item->jumlah) : 0;
            });

        // Hitung total jasa
        $totalJasa = $transaksi->serviceJasaItems->sum('harga_jasa');

        // Hitung discount
        $discount = 0;
        if ($transaksi->diskon > 0) {
            $subtotal = $transaksi->total_barang + $transaksi->total_jasa;
            $discount = $transaksi->tipe_diskon === 'persentase'
                ? ($subtotal * $transaksi->diskon) / 100
                : $transaksi->diskon;
        }

        // Total pendapatan (sebelum diskon)
        $jumlah = $transaksi->total_barang + $transaksi->total_jasa;

        // Laba sparepart
        $labaSpart = $transaksi->total_barang - $totalModal;

        // Nomor invoice berdasarkan urutan transaksi minggu ini
        $invoiceNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

        // Data mobil
        $carName = $transaksi->pelangganMobil->merk_mobil ?? 'SERVICE';
        $nopol   = $transaksi->pelangganMobil->nopol ?? 'UNKNOWN';

        // Format invoice
        $itemCode = sprintf(
            "INV %s/%s/%s",
            $invoiceNumber,
            $carName,
            $nopol
        );

        return [
            'tanggal'    => Carbon::parse($transaksi->tanggal_service)->format('d/m'),
            'item'       => $itemCode,
            'jumlah'     => $jumlah,
            'discount'   => $discount,
            'modal'      => $totalModal,
            'jasa'       => $totalJasa,
            'laba_spart' => $labaSpart,
            'invoice'    => $transaksi->invoice,
        ];
    });
}



    private function getPiutangData()
    {
        return TransaksiService::whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd])
            ->where('sisa_pembayaran', '>', 0)
            ->sum('sisa_pembayaran');
    }

    private function getDpData()
    {
        // DP adalah pembayaran pertama yang dilakukan jika transaksi tidak lunas
        $dpTotal = 0;
        
        $transaksiIds = TransaksiService::whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd])
            ->where('status_pembayaran', '!=', 'lunas')
            ->pluck('id');

        foreach ($transaksiIds as $transaksiId) {
            $firstPayment = ServicePayment::where('transaksi_service_id', $transaksiId)
                ->orderBy('tanggal_bayar')
                ->orderBy('created_at')
                ->first();
                
            if ($firstPayment) {
                $dpTotal += $firstPayment->jumlah_bayar;
            }
        }

        return $dpTotal;
    }

    public function render()
    {
        return view('livewire.laporan-mingguan', [
            'weeklyData' => $this->weeklyData,
            'summary' => $this->summary,
            'operationalData' => $this->operationalData,
            'operationalSummary' => $this->operationalSummary,
            'sparepartData' => $this->sparepartData,
            'sparepartSummary' => $this->sparepartSummary,
        ]);
    }
}