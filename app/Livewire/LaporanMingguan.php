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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPendapatanExport;
use App\Exports\LaporanOperasionalExport;
use App\Exports\LaporanSparepartExport;

#[Layout('layouts.app')]    

class LaporanMingguan extends Component
{
    // Existing properties
    public $weekStart;
    public $weekEnd;
    public $weekNumber;
    public $monthName;
    public $year;
    public $activeTab = 'pendapatan';
    public $isCurrentWeek = false;
    public $canGoToPrevious = true;
    public $canGoToNext = false;

    // New properties for month/year navigation
    public $selectedMonth;
    public $selectedYear;
    public $currentWeekNumber = 1;
    public $isCurrentMonthSelected = true;
    public $availableWeeks = [];

    public function mount()
    {
        // Initialize dengan bulan dan tahun saat ini
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->currentWeekNumber = $this->getCurrentWeekNumberInMonth();
        
        $this->loadWeekData();
    }

    public function updatedSelectedMonth()
    {
        $this->checkIfCurrentMonth();
        $this->currentWeekNumber = 1; // Reset ke minggu pertama saat ganti bulan
        $this->loadWeekData();
    }

    public function updatedSelectedYear()
    {
        $this->checkIfCurrentMonth();
        $this->currentWeekNumber = 1; // Reset ke minggu pertama saat ganti tahun
        $this->loadWeekData();
    }

    private function checkIfCurrentMonth()
    {
        $this->isCurrentMonthSelected = 
            $this->selectedMonth == now()->month && 
            $this->selectedYear == now()->year;
    }

    private function getCurrentWeekNumberInMonth()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $weekNumber = 1;
        
        $current = $startOfMonth->copy();
        
        // Cari Senin pertama atau hari pertama bulan
        while ($current->dayOfWeek !== Carbon::MONDAY && $current->day != 1) {
            $current->addDay();
        }
        
        $weekStart = $current->copy();
        
        // Hitung minggu sampai minggu saat ini
        while ($weekStart->lte($now)) {
            $weekEnd = $weekStart->copy()->addDays(6);
            
            if ($now->between($weekStart, $weekEnd)) {
                break;
            }
            
            $weekStart = $weekEnd->copy()->addDay();
            while ($weekStart->dayOfWeek !== Carbon::MONDAY && $weekStart->month == $now->month) {
                $weekStart->addDay();
            }
            
            if ($weekStart->month == $now->month) {
                $weekNumber++;
            }
        }
        
        return $weekNumber;
    }

    private function loadWeekData()
    {
        // Hitung minggu-minggu dalam bulan yang dipilih
        $this->availableWeeks = $this->getWeeksInMonth($this->selectedYear, $this->selectedMonth);
        
        // Pastikan week number tidak melebihi jumlah minggu yang ada
        if ($this->currentWeekNumber > count($this->availableWeeks)) {
            $this->currentWeekNumber = count($this->availableWeeks);
        }
        
        // Set week data berdasarkan minggu yang dipilih
        $weekData = $this->availableWeeks[$this->currentWeekNumber - 1];
        $this->weekStart = $weekData['start'];
        $this->weekEnd = $weekData['end'];
        $this->weekNumber = $weekData['weekNumber'];
        $this->isCurrentWeek = $weekData['isCurrentWeek'];
        
        // Set month name and year
        $startDate = Carbon::parse($this->weekStart);
        $this->monthName = $startDate->translatedFormat('F');
        $this->year = $startDate->year;
        
        // Update navigation state
        $this->updateNavigationState();
    }

    private function getWeeksInMonth($year, $month)
    {
        $weeks = [];
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $now = now();
        
        $weekNumber = 1;
        $current = $startOfMonth->copy();
        
        // Cari hari Senin pertama di bulan ini atau gunakan tanggal 1
        while ($current->dayOfWeek != Carbon::MONDAY && $current->day != 1 && $current->lte($endOfMonth)) {
            $current->addDay();
        }
        
        $weekStart = $current->copy();
        
        while ($weekStart->lte($endOfMonth)) {
            // Tentukan akhir minggu (Sabtu - sesuai dengan logika asli Anda)
            $weekEnd = $weekStart->copy()->addDays(5); // Senin + 5 = Sabtu
            
            // Jika weekEnd melewati akhir bulan, set ke akhir bulan
            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();
            }
            
            // Cek apakah ini minggu saat ini
            $isCurrentWeek = $now->between($weekStart, $weekEnd) && 
                           $this->selectedMonth == $now->month && 
                           $this->selectedYear == $now->year;
            
            $weeks[] = [
                'weekNumber' => $weekNumber,
                'start' => $weekStart->format('Y-m-d'),
                'end' => $weekEnd->format('Y-m-d'),
                'dateRange' => $weekStart->format('d') . '-' . $weekEnd->format('d M'),
                'isCurrentWeek' => $isCurrentWeek
            ];
            
            // Pindah ke Senin berikutnya
            $weekStart = $weekEnd->copy()->addDay();
            while ($weekStart->dayOfWeek != Carbon::MONDAY && $weekStart->lte($endOfMonth)) {
                $weekStart->addDay();
            }
            
            $weekNumber++;
            
            // Break jika sudah melewati akhir bulan
            if ($weekStart->gt($endOfMonth)) {
                break;
            }
        }
        
        return $weeks;
    }

    private function updateNavigationState()
    {
        // Cek apakah bisa ke minggu sebelumnya
        $this->canGoToPrevious = true; // Selalu bisa ke minggu sebelumnya
        
        // Cek apakah bisa ke minggu berikutnya
        if ($this->isCurrentMonthSelected) {
            $this->canGoToNext = !$this->isCurrentWeek;
        } else {
            // Jika bukan bulan saat ini, cek apakah masih ada minggu berikutnya di bulan yang dipilih
            $selectedDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
            $currentDate = now();
            
            if ($selectedDate->lt($currentDate->startOfMonth())) {
                // Bulan di masa lalu, bisa next jika masih ada minggu
                $this->canGoToNext = $this->currentWeekNumber < count($this->availableWeeks);
            } else {
                $this->canGoToNext = false;
            }
        }
    }

    public function selectWeek($weekNumber)
    {
        $this->currentWeekNumber = $weekNumber;
        $this->loadWeekData();
    }

    public function previousWeek()
    {
        if ($this->currentWeekNumber > 1) {
            $this->currentWeekNumber--;
        } else {
            // Pindah ke bulan sebelumnya, minggu terakhir
            $this->previousMonth();
            $this->currentWeekNumber = count($this->availableWeeks);
        }
        
        $this->loadWeekData();
    }

    public function nextWeek()
    {
        if ($this->currentWeekNumber < count($this->availableWeeks)) {
            $this->currentWeekNumber++;
            $this->loadWeekData();
        } else {
            // Pindah ke bulan berikutnya, minggu pertama
            if (!$this->isCurrentMonthSelected) {
                $this->nextMonth();
                $this->currentWeekNumber = 1;
                $this->loadWeekData();
            }
        }
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->checkIfCurrentMonth();
        $this->loadWeekData();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        
        // Jangan lewati bulan saat ini
        $now = now();
        if ($date->year < $now->year || ($date->year == $now->year && $date->month <= $now->month)) {
            $this->selectedMonth = $date->month;
            $this->selectedYear = $date->year;
            $this->checkIfCurrentMonth();
            $this->loadWeekData();
        }
    }

    public function goToCurrentMonth()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->currentWeekNumber = $this->getCurrentWeekNumberInMonth();
        $this->checkIfCurrentMonth();
        $this->loadWeekData();
    }

    // Keep the old methods for backward compatibility
    private function initializeCurrentWeek()
    {
        $this->goToCurrentMonth();
    }

    private function calculateWeekInMonth()
    {
        // This method is now handled by loadWeekData()
        // Kept for backward compatibility
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Export methods
    public function exportPendapatan()
    {
        $weeklyData = $this->getServiceTransactions();
        $summary = $this->getSummaryProperty();
        
        $filename = 'laporan-pendapatan-minggu-' . $this->weekNumber . '-' . strtolower($this->monthName) . '-' . $this->year . '.xlsx';
        
        return Excel::download(new LaporanPendapatanExport($weeklyData, $summary, [
            'weekStart' => $this->weekStart,
            'weekEnd' => $this->weekEnd,
            'weekNumber' => $this->weekNumber,
            'monthName' => $this->monthName,
            'year' => $this->year
        ]), $filename);
    }

    public function exportOperasional()
    {
        $operationalData = $this->getOperationalDataProperty();
        $operationalSummary = $this->getOperationalSummaryProperty();
        
        $filename = 'laporan-operasional-minggu-' . $this->weekNumber . '-' . strtolower($this->monthName) . '-' . $this->year . '.xlsx';
        
        return Excel::download(new LaporanOperasionalExport($operationalData, $operationalSummary, [
            'weekStart' => $this->weekStart,
            'weekEnd' => $this->weekEnd,
            'weekNumber' => $this->weekNumber,
            'monthName' => $this->monthName,
            'year' => $this->year
        ]), $filename);
    }

    public function exportSparepart()
    {
        $sparepartData = $this->getSparepartDataProperty();
        $sparepartSummary = $this->getSparepartSummaryProperty();
        
        $filename = 'laporan-sparepart-minggu-' . $this->weekNumber . '-' . strtolower($this->monthName) . '-' . $this->year . '.xlsx';
        
        return Excel::download(new LaporanSparepartExport($sparepartData, $sparepartSummary, [
            'weekStart' => $this->weekStart,
            'weekEnd' => $this->weekEnd,
            'weekNumber' => $this->weekNumber,
            'monthName' => $this->monthName,
            'year' => $this->year
        ]), $filename);
    }

    public function getWeeklyDataProperty()
    {
        return $this->getServiceTransactions();
    }

    public function getSummaryProperty()
    {
        $weeklyData = $this->getServiceTransactions();
        $operationalExpenses = $this->getOperationalDataProperty();
        $piutangPerInvoice = $this->getPiutangData();

        $totalPendapatanBruto = $weeklyData->sum('jumlah');
        $totalDiscount = $weeklyData->sum('discount');
        $totalModal = $weeklyData->sum('modal');
        $totalJasa = $weeklyData->sum('jasa');
        $totalLabaSpart = $weeklyData->sum('laba_spart');
        $totalOperasional = $operationalExpenses->sum('jumlah');
        $dpPerInvoice = $this->getDpData();
        $totalDp = $dpPerInvoice->sum('dp');

        $totalPiutang = $piutangPerInvoice->sum('total_piutang');

        $pendapatanBersih = $totalPendapatanBruto - $totalModal - $totalDiscount - $totalOperasional;

        return [
            'total_pendapatan_bruto' => $totalPendapatanBruto,
            'total_discount' => $totalDiscount,
            'total_modal' => $totalModal,
            'total_jasa' => $totalJasa,
            'total_laba_spart' => $totalLabaSpart,
            'total_piutang' => $totalPiutang,
            'piutang_per_invoice' => $piutangPerInvoice,
            'dp_per_invoice' => $dpPerInvoice,
            'total_dp' => $totalDp,
            'operasional' => $totalOperasional,
            'pendapatan_bersih' => $pendapatanBersih,
        ];
    }

    public function getPiutangDetailProperty()
    {
        $allTransaksi = TransaksiService::whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd])
            ->with(['pelangganMobil'])
            ->orderBy('tanggal_service')
            ->get();

        $piutangDetails = [];
        foreach ($allTransaksi as $index => $transaksi) {
            if ($transaksi->sisa_pembayaran > 0) {
                $invoiceNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $piutangDetails[] = [
                    'invoice_number' => $invoiceNumber,
                    'invoice_code' => $transaksi->invoice,
                    'sisa_piutang' => $transaksi->sisa_pembayaran,
                    'tanggal' => Carbon::parse($transaksi->tanggal_service)->format('d/m'),
                    'pelanggan' => $transaksi->pelangganMobil->merk_mobil ?? 'N/A',
                    'nopol' => $transaksi->pelangganMobil->nopol ?? 'N/A',
                ];
            }
        }

        return collect($piutangDetails);
    }

    public function getOperationalDataProperty()
    {
        return PengeluaranOperasional::whereBetween('tanggal', [$this->weekStart, $this->weekEnd])
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal'    => Carbon::parse($item->tanggal)->format('j-M-y'),
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
        $serviceItems = ServiceBarangItem::whereHas('transaksiService', function ($query) {
                $query->whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd]);
            })
            ->with(['transaksiService.pelangganMobil', 'barang', 'pembelian'])
            ->get()
            ->map(function ($item) {
                $hargaBeli = $item->is_manual
                    ? $item->harga_beli_manual
                    : ($item->pembelian ? $item->pembelian->harga_beli : 0);

                $totalModal = $hargaBeli * $item->jumlah;

                $pelangganMobil = $item->transaksiService->pelangganMobil;
                $namaBarang = $item->is_manual ? $item->nama_barang_manual : ($item->barang->nama ?? 'N/A');
                
                $itemName = $namaBarang;
                if ($pelangganMobil) {
                    $merkMobil = $pelangganMobil->merk_mobil ?? 'N/A';
                    $nopol = $pelangganMobil->nopol ?? 'N/A';
                    $itemName = strtoupper(trim($merkMobil)) . '/' . strtoupper($nopol) . '/' . $namaBarang;
                }

                return [
                    'tanggal' => Carbon::parse($item->transaksiService->tanggal_service)->format('j-M-y'),
                    'item'    => $itemName,
                    'jumlah'  => $totalModal,
                    'source'  => 'service',
                ];
            });

        $penjualanItems = PenjualanItem::whereHas('penjualan', function ($query) {
            $query->whereBetween('tanggal', [$this->weekStart, $this->weekEnd]);
        })
        ->with(['penjualan', 'barang', 'pembelian'])
        ->get()
        ->map(function ($item) {
            $hargaBeli = $item->harga_beli_manual
                ?? ($item->pembelian ? $item->pembelian->harga_beli : 0);

            $totalModal = $hargaBeli * $item->jumlah;

            $namaBarang = $item->nama_barang_manual ?? ($item->barang->nama ?? 'Item tidak ditemukan');

            $itemName = '#' . $item->penjualan->id . ' ' . $namaBarang;

            return [
                'tanggal' => Carbon::parse($item->penjualan->tanggal)->format('j-M-y'),
                'item'    => $itemName,
                'jumlah'  => $totalModal,
                'source'  => 'penjualan',
            ];
        });

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
            $totalModal = $transaksi->serviceBarangItems
                ->filter(function ($item) {
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

            $totalJasa = $transaksi->serviceJasaItems->sum('harga_jasa');

            $discount = 0;
            if ($transaksi->diskon > 0) {
                $subtotal = $transaksi->total_barang + $transaksi->total_jasa;
                $discount = $transaksi->tipe_diskon === 'persentase'
                    ? ($subtotal * $transaksi->diskon) / 100
                    : $transaksi->diskon;
            }

            $jumlah = $transaksi->total_barang + $transaksi->total_jasa;

            $labaSpart = $transaksi->total_barang - $totalModal;

            $invoiceNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $carName = $transaksi->pelangganMobil->merk_mobil ?? 'SERVICE';
            $nopol   = $transaksi->pelangganMobil->nopol ?? 'UNKNOWN';

            $itemCode = sprintf(
                "INV %s/%s/%s",
                $invoiceNumber,
                strtoupper($carName),
                strtoupper($nopol)
            );

            return [
                'tanggal'    => Carbon::parse($transaksi->tanggal_service)->format('j-M-y'),
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
            ->select('invoice', \DB::raw('SUM(sisa_pembayaran) as total_piutang'))
            ->groupBy('invoice')
            ->get();
    }

    private function getDpData()
    {
        $result = collect();

        $transaksiList = TransaksiService::whereBetween('tanggal_service', [$this->weekStart, $this->weekEnd])
            ->where('status_pembayaran', '!=', 'lunas')
            ->get(['id', 'invoice']);

        foreach ($transaksiList as $transaksi) {
            $firstPayment = ServicePayment::where('transaksi_service_id', $transaksi->id)
                ->orderBy('tanggal_bayar')
                ->orderBy('created_at')
                ->first();

            if ($firstPayment) {
                $result->push([
                    'invoice' => $transaksi->invoice,
                    'dp' => $firstPayment->jumlah_bayar,
                ]);
            }
        }

        return $result;
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
            'piutangDetail' => $this->piutangDetail,
        ]);
    }
}