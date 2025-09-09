<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiService;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]

class LaporanPiutang extends Component
{
    public $selectedPeriod = 'today';
    public $customDateFrom;
    public $customDateTo;
    public $selectedLocation = 'all';
    public $refreshInterval = 5000; // milliseconds
    
    // KPI Properties
    public $totalPendapatan = 0;
    public $totalPengeluaran = 0;
    public $labaBersih = 0;
    public $totalPiutang = 0;
    public $jumlahTransaksi = 0;
    public $growthRate = 0;
    
    // Chart Data
    public $chartData = [];
    public $piutangData = [];
    public $topTransaksi = [];
    
    protected $listeners = ['refreshDashboard' => 'loadData'];

    public function mount()
    {
        $this->customDateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->customDateTo = Carbon::now()->format('Y-m-d');
        $this->loadData();
    }

    public function updatedSelectedPeriod()
    {
        $this->setDateRange();
        $this->loadData();
    }

    public function updatedCustomDateFrom()
    {
        if ($this->selectedPeriod === 'custom') {
            $this->loadData();
        }
    }

    public function updatedCustomDateTo()
    {
        if ($this->selectedPeriod === 'custom') {
            $this->loadData();
        }
    }

    private function setDateRange()
    {
        switch ($this->selectedPeriod) {
            case 'today':
                $this->customDateFrom = Carbon::now()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->customDateFrom = Carbon::yesterday()->format('Y-m-d');
                $this->customDateTo = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'this_week':
                $this->customDateFrom = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $this->customDateFrom = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->customDateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->customDateFrom = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_quarter':
                $this->customDateFrom = Carbon::now()->startOfQuarter()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'this_year':
                $this->customDateFrom = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->customDateTo = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function loadData()
    {
        $this->calculateKPIs();
        $this->loadChartData();
        $this->loadPiutangData();
        $this->loadTopTransaksi();
    }

    private function calculateKPIs()
    {
        $dateFrom = Carbon::parse($this->customDateFrom)->startOfDay();
        $dateTo = Carbon::parse($this->customDateTo)->endOfDay();

        // Total Pendapatan Service
        $pendapatanService = TransaksiService::whereBetween('tanggal_service', [$dateFrom, $dateTo])
            ->sum('total_keseluruhan');

        // Total Pendapatan Penjualan Spare Part
        $pendapatanSparepart = Penjualan::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->sum('total_harga');

        $this->totalPendapatan = $pendapatanService + $pendapatanSparepart;

        // Total Pengeluaran (Pembelian)
        $this->totalPengeluaran = Pembelian::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->sum(DB::raw('harga_beli * jumlah'));

        // Laba Bersih
        $this->labaBersih = $this->totalPendapatan - $this->totalPengeluaran;

        // Total Piutang Outstanding
        $this->totalPiutang = TransaksiService::where('status_pembayaran', '!=', 'lunas')
            ->sum('sisa_pembayaran');

        // Jumlah Transaksi
        $transaksiService = TransaksiService::whereBetween('tanggal_service', [$dateFrom, $dateTo])
            ->count();
        
        $transaksiPenjualan = Penjualan::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->count();

        $this->jumlahTransaksi = $transaksiService + $transaksiPenjualan;

        // Growth Rate (dibandingkan periode sebelumnya)
        $this->calculateGrowthRate($dateFrom, $dateTo);
    }

    private function calculateGrowthRate($dateFrom, $dateTo)
    {
        $daysDiff = $dateTo->diffInDays($dateFrom) + 1;
        $previousDateFrom = $dateFrom->copy()->subDays($daysDiff);
        $previousDateTo = $dateFrom->copy()->subDay();

        $previousPendapatanService = TransaksiService::whereBetween('tanggal_service', [$previousDateFrom, $previousDateTo])
            ->sum('total_keseluruhan');

        $previousPendapatanSparepart = Penjualan::whereBetween('tanggal', [$previousDateFrom, $previousDateTo])
            ->sum('total_harga');

        $previousTotal = $previousPendapatanService + $previousPendapatanSparepart;

        if ($previousTotal > 0) {
            $this->growthRate = (($this->totalPendapatan - $previousTotal) / $previousTotal) * 100;
        } else {
            $this->growthRate = $this->totalPendapatan > 0 ? 100 : 0;
        }
    }

    private function loadChartData()
    {
        $dateFrom = Carbon::parse($this->customDateFrom);
        $dateTo = Carbon::parse($this->customDateTo);

        $this->chartData = [];
        
        // Generate daily data for chart
        $currentDate = $dateFrom->copy();
        while ($currentDate <= $dateTo) {
            $dayStart = $currentDate->copy()->startOfDay();
            $dayEnd = $currentDate->copy()->endOfDay();

            $dailyService = TransaksiService::whereBetween('tanggal_service', [$dayStart, $dayEnd])
                ->sum('total_keseluruhan');

            $dailySparepart = Penjualan::whereBetween('tanggal', [$dayStart, $dayEnd])
                ->sum('total_harga');

            $dailyPembelian = Pembelian::whereBetween('tanggal', [$dayStart, $dayEnd])
                ->whereNull('deleted_at')
                ->sum(DB::raw('harga_beli * jumlah'));

            $this->chartData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'date_formatted' => $currentDate->format('d/m'),
                'pendapatan' => $dailyService + $dailySparepart,
                'pengeluaran' => $dailyPembelian,
                'laba' => ($dailyService + $dailySparepart) - $dailyPembelian,
            ];

            $currentDate->addDay();
        }
    }

    private function loadPiutangData()
    {
        $this->piutangData = TransaksiService::with(['pelangganMobil'])
            ->where('status_pembayaran', '!=', 'lunas')
            ->whereNull('deleted_at')
            ->orderBy('jatuh_tempo', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($transaksi) {
                $overdueDays = $transaksi->jatuh_tempo ? Carbon::now()->diffInDays($transaksi->jatuh_tempo, false) : 0;
                return [
                    'id' => $transaksi->id,
                    'invoice' => $transaksi->invoice,
                    'pelanggan' => $transaksi->pelangganMobil->nama_pelanggan ?? 'N/A',
                    'nopol' => $transaksi->pelangganMobil->nopol ?? 'N/A',
                    'jumlah' => $transaksi->sisa_pembayaran,
                    'jatuh_tempo' => $transaksi->jatuh_tempo,
                    'overdue_days' => $overdueDays,
                    'status' => $overdueDays > 0 ? 'overdue' : 'normal',
                ];
            })
            ->toArray();
    }

    private function loadTopTransaksi()
    {
        $dateFrom = Carbon::parse($this->customDateFrom)->startOfDay();
        $dateTo = Carbon::parse($this->customDateTo)->endOfDay();

        $this->topTransaksi = TransaksiService::with(['pelangganMobil'])
            ->whereBetween('tanggal_service', [$dateFrom, $dateTo])
            ->whereNull('deleted_at')
            ->orderBy('total_keseluruhan', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($transaksi) {
                return [
                    'id' => $transaksi->id,
                    'invoice' => $transaksi->invoice,
                    'pelanggan' => $transaksi->pelangganMobil->nama_pelanggan ?? 'N/A',
                    'nopol' => $transaksi->pelangganMobil->nopol ?? 'N/A',
                    'total' => $transaksi->total_keseluruhan,
                    'tanggal' => $transaksi->tanggal_service,
                    'status_pembayaran' => $transaksi->status_pembayaran,
                ];
            })
            ->toArray();
    }

    public function export($type = 'excel')
    {
        // Implement export functionality
        $this->dispatch('showNotification', [
            'message' => 'Export berhasil!',
            'type' => 'success',
        ]);

    }

    public function render()
    {
        return view('livewire.laporan-piutang');
    }
}