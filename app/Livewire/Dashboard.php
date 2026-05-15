<?php

namespace App\Livewire;

use App\Models\TransaksiService;
use App\Models\ServicePayment;
use App\Models\PengeluaranOperasional;
use App\Models\Barang;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends RiwayatService // Lepas inheritance jika RiwayatService tidak diperlukan
{
    public int $limit = 10;

    // 1. STATISTIK UTAMA (Computed Property)
    public function getDashboardStatsProperty(): array
    {
        $bulanIni = Carbon::now()->startOfMonth();

        return [
            'total'       => TransaksiService::where('created_at', '>=', $bulanIni)->count(),
            'belum_lunas' => TransaksiService::whereIn('status_pembayaran', ['belum', 'sebagian'])->count(),
            'selesai'     => TransaksiService::where('status_pekerjaan', 'selesai')->count(),
        ];
    }

    // 2. STATISTIK OWNER & LAPORAN (Computed Property)
    public function getOwnerStatsProperty(): array
    {
        if (Auth::user()->role !== 'owner') return [];

        // --- 1. INISIALISASI WAKTU ---
        $now            = Carbon::now();
        $bulanIni       = $now->copy()->startOfMonth();
        $bulanLaluStart = $now->copy()->subMonth()->startOfMonth();
        $bulanLaluEnd   = $now->copy()->subMonth()->endOfMonth();
        $tujuhHariLalu  = $now->copy()->subDays(7);

        // --- 2. PENDAPATAN & PERBANDINGAN ---
        $pendapatanIni  = (float) ServicePayment::whereDate('tanggal_bayar', '>=', $bulanIni)->sum('jumlah_bayar');
        $pendapatanLalu = (float) ServicePayment::whereDate('tanggal_bayar', '>=', $bulanLaluStart)
                            ->whereDate('tanggal_bayar', '<=', $bulanLaluEnd)
                            ->sum('jumlah_bayar');

        $pctPendapatan = $pendapatanLalu > 0
            ? round((($pendapatanIni - $pendapatanLalu) / $pendapatanLalu) * 100)
            : ($pendapatanIni > 0 ? 100 : 0);

        // --- 3. TRANSAKSI & OPERASIONAL ---
        $totalTransaksiBulan = TransaksiService::whereDate('created_at', '>=', $bulanIni)->count();
        $avgPerOrder         = $totalTransaksiBulan > 0 ? round($pendapatanIni / $totalTransaksiBulan) : 0;
        $pengeluaranIni      = (float) PengeluaranOperasional::whereDate('tanggal', '>=', $bulanIni)->sum('jumlah_pengeluaran');
        $labaBersih          = $pendapatanIni - $pengeluaranIni;

        // --- 4. PIUTANG (Penyebab Error number_format diperbaiki di sini) ---
        $piutangQuery = TransaksiService::whereIn('status_pembayaran', ['belum', 'sebagian']);
        
        // Pastikan memanggil sum() agar menjadi float/int, bukan objek Builder
        $nominalPiutang  = (float) $piutangQuery->sum('sisa_pembayaran');
        $countBelumLunas = $piutangQuery->count();
        $piutangLama     = TransaksiService::whereIn('status_pembayaran', ['belum', 'sebagian'])
                            ->where('created_at', '<=', $tujuhHariLalu)
                            ->count();

        // --- 5. STATUS PEKERJAAN ---
        $statusSelesai = TransaksiService::whereDate('created_at', '>=', $bulanIni)->where('status_pekerjaan', 'selesai')->count();
        $statusProses  = TransaksiService::whereDate('created_at', '>=', $bulanIni)->where('status_pekerjaan', 'sedang_dikerjakan')->count();
        $statusBelum   = TransaksiService::whereDate('created_at', '>=', $bulanIni)->where('status_pekerjaan', 'belum_dikerjakan')->count();

        // --- 6. CHART 7 HARI TERAKHIR ---
        $labels_7_hari = [];
        $data_7_hari   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::now()->subDays($i);
            $labels_7_hari[] = $tgl->translatedFormat('D'); 
            $data_7_hari[]   = (int) ServicePayment::whereDate('tanggal_bayar', $tgl->toDateString())->sum('jumlah_bayar');
        }

        // --- 7. TOP JASA ---
        $topJasaData = \App\Models\ServiceJasaItem::whereHas('transaksiService', fn($q) => $q->where('created_at', '>=', $bulanIni))
            ->select('nama_jasa', \Illuminate\Support\Facades\DB::raw('count(*) as count, sum(harga_jasa) as total'))
            ->groupBy('nama_jasa')->orderByDesc('count')->limit(5)->get();
        
        $maxCount = $topJasaData->first()->count ?? 1;
        $topJasa  = $topJasaData->map(fn($j) => [
            'nama'  => $j->nama_jasa, 
            'count' => $j->count, 
            'total' => $j->total, 
            'pct'   => round(($j->count / $maxCount) * 100),
        ])->toArray();

        // --- 8. STOK MENIPIS ---
        $stokMenipis = \App\Models\Barang::withSum('pembelians as total_stok', 'jumlah_tersisa')
            ->having('total_stok', '<', 5)
            ->count();

        // --- 9. RETURN ARRAY (SINKRON DENGAN BLADE) ---
        return [
            'pendapatan_bulan_ini'  => $pendapatanIni,
            'pengeluaran_bulan_ini' => $pengeluaranIni,
            'total_transaksi_bulan' => $totalTransaksiBulan,
            'total_piutang'         => $nominalPiutang, // Sekarang berupa ANGKA, bukan Builder
            'piutang_total'         => $nominalPiutang,
            'pct_pendapatan'        => $pctPendapatan,
            'avg_per_order'         => $avgPerOrder,
            'count_belum_lunas'     => $countBelumLunas,
            'labels_7_hari'         => $labels_7_hari,
            'data_7_hari'           => $data_7_hari,
            'data_servis_7_hari'    => $data_7_hari,
            'data_barang_7_hari'    => $data_7_hari,
            'piutang_lama'          => $piutangLama,
            'top_jasa'              => $topJasa,
            'status_selesai'        => $statusSelesai,
            'status_proses'         => $statusProses,
            'status_belum'          => $statusBelum,
            'operasional'           => $pengeluaranIni,
            'laba_bersih'           => $labaBersih,
            'stok_menipis'          => $stokMenipis,
        ];
    }
    // 3. TRANSAKSI TERAKHIR
    public function getRecentTransactionsProperty()
    {
        return TransaksiService::with('pelangganMobil')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            // Ganti 'stats' menjadi 'dashboardStats'
            'dashboardStats'     => $this->dashboardStats, 
            'ownerStats'         => $this->ownerStats,
            'recentTransactions' => $this->recentTransactions,
            'data' => [
                'pendapatan_bulan_ini' => $this->ownerStats['pendapatan_ini'] ?? 0,
            ]
        ]);
    }
}