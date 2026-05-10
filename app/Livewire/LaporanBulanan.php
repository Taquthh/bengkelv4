<?php

    namespace App\Livewire;

    use App\Exports\LaporanBulananExport;
use App\Exports\LaporanPiutangExport;
use Livewire\Component;
    use Livewire\Attributes\Layout;
    use Carbon\Carbon;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;

    use App\Models\TransaksiService;
    use App\Models\ServiceBarangItem;
    use App\Models\ServiceJasaItem;
    use App\Models\ServicePayment;
    use App\Models\PengeluaranOperasional;
    use App\Models\PenjualanItem;
    use Maatwebsite\Excel\Facades\Excel;

    #[Layout('layouts.app')]
    class LaporanBulanan extends Component
    {
        public $month;         // 1..12
        public $year;          // YYYY
        public $startDate;     // YYYY-MM-DD
        public $endDate;       // YYYY-MM-DD
        public $activeTab = 'bulanan';

        public function mount()
        {
            $now = Carbon::now();
            $this->month = (int)$now->month;
            $this->year  = (int)$now->year;
            $this->recomputePeriod();
        }

        public function previousMonth()
        {
            $date = Carbon::create($this->year, $this->month, 1)->subMonth();
            $this->month = (int)$date->month;
            $this->year  = (int)$date->year;
            $this->recomputePeriod();
        }

        public function nextMonth()
        {
            $date = Carbon::create($this->year, $this->month, 1)->addMonth();
            $this->month = (int)$date->month;
            $this->year  = (int)$date->year;
            $this->recomputePeriod();
        }

        public function setActiveTab($tab)
        {
            $this->activeTab = $tab;
        }

        private function recomputePeriod()
        {
            $start = Carbon::create($this->year, $this->month, 1);
            $end   = $start->copy()->endOfMonth();
            // Per weekly convention: Monday–Saturday windows
            $this->startDate = $start->toDateString();
            $this->endDate   = $end->toDateString();
        }

        // --- Helpers: weeks in month (Mon..Sat) ---
        private function getWeeksInMonth(): array
        {
            $monthStart = Carbon::create($this->year, $this->month, 1)->startOfMonth();
            $monthEnd   = $monthStart->copy()->endOfMonth();

            // find first Monday on/after monthStart
            $current = $monthStart->copy();
            while ($current->dayOfWeek !== Carbon::MONDAY && $current->lte($monthEnd)) {
                $current->addDay();
            }

            $weeks = [];
            $index = 1;
            while ($current->lte($monthEnd)) {
                $weekStart = $current->copy()->startOfWeek(Carbon::MONDAY);
                $weekEnd   = $current->copy()->endOfWeek(Carbon::SATURDAY);
                // clamp to month boundaries
                if ($weekStart->lt($monthStart)) $weekStart = $monthStart->copy();
                if ($weekEnd->gt($monthEnd))     $weekEnd   = $monthEnd->copy();

                $weeks[] = [
                    'index' => $index,
                    'start' => $weekStart->toDateString(),
                    'end'   => $weekEnd->toDateString(),
                ];

                $index++;
                $current->addWeek();
            }

            // Edge case: if month starts after Monday (e.g., starts Wed and no Monday within Month?), ensure at least 4–5 buckets
            if (empty($weeks)) {
                $weeks[] = [
                    'index' => 1,
                    'start' => $monthStart->toDateString(),
                    'end'   => $monthEnd->toDateString(),
                ];
            }

            return $weeks;
        }

        // --- Core aggregations per range ---
        private function getServiceTransactionsBetween(string $start, string $end): Collection
        {
            $transaksi = TransaksiService::whereBetween('tanggal_service', [$start, $end])
                ->with(['pelangganMobil', 'serviceBarangItems.barang', 'serviceBarangItems.pembelian', 'serviceJasaItems'])
                ->orderBy('tanggal_service')
                ->get();

            return $transaksi->map(function ($trx) {
                // modal sparepart
                $totalModal = $trx->serviceBarangItems
                    ->filter(function ($item) {
                        return $item->is_manual ? !empty($item->nama_barang_manual) : !empty($item->barang);
                    })
                    ->sum(function ($item) {
                        if ($item->is_manual) {
                            return (int)$item->harga_beli_manual * (int)$item->jumlah;
                        }
                        return $item->pembelian ? ((int)$item->pembelian->harga_beli * (int)$item->jumlah) : 0;
                    });

                $totalJasa = (int)$trx->serviceJasaItems->sum('harga_jasa');

                $discount = 0;
                if ($trx->diskon > 0) {
                    $subtotal = (int)$trx->total_barang + (int)$trx->total_jasa;
                    $discount = $trx->tipe_diskon === 'persentase'
                        ? (int) round(($subtotal * $trx->diskon) / 100)
                        : (int)$trx->diskon;
                }

                $jumlah = (int)$trx->total_barang + (int)$trx->total_jasa;
                $labaSpart = (int)$trx->total_barang - $totalModal;

                return [
                    'jumlah'     => $jumlah,
                    'discount'   => $discount,
                    'modal'      => $totalModal,
                    'jasa'       => $totalJasa,
                    'laba_spart' => $labaSpart,
                    'invoice'    => $trx->invoice,
                    'tanggal'    => $trx->tanggal_service,
                ];
            });
        }

        private function getOperationalTotalBetween(string $start, string $end): int
        {
            return (int) PengeluaranOperasional::whereBetween('tanggal', [$start, $end])
                ->sum('jumlah_pengeluaran');
        }

        private function getPiutangTotalBetween(string $start, string $end): int
        {
            $total = 0;

            $transaksiList = TransaksiService::whereBetween('tanggal_service', [$start, $end])
                ->get();

            foreach ($transaksiList as $trx) {
                // hitung diskon dan grand total
                $subtotal = (int)$trx->total_barang + (int)$trx->total_jasa;
                $discount = 0;
                if ($trx->diskon > 0) {
                    $discount = $trx->tipe_diskon === 'persentase'
                        ? (int) round(($subtotal * $trx->diskon) / 100)
                        : (int)$trx->diskon;
                }
                $grandTotal = max(0, $subtotal - $discount);

                // DP diambil dari pembayaran pertama
                $firstPayment = ServicePayment::where('transaksi_service_id', $trx->id)
                    ->orderBy('tanggal_bayar')
                    ->orderBy('created_at')
                    ->first();
                $dpPertama = $firstPayment ? (int)$firstPayment->jumlah_bayar : 0;

                $initialPiutang = max(0, $grandTotal - $dpPertama);

                // Jika belum lunas namun ada pembayaran lanjutan, batasi maksimal sisa_pembayaran agar tidak overstate.
                $sisa = (int) $trx->sisa_pembayaran;
                if ($sisa <= 0 || $trx->status_pembayaran === 'lunas') {
                    $initialPiutang = 0;
                } else {
                    $initialPiutang = min($initialPiutang, $sisa);
                }

                $total += $initialPiutang;
            }

            return $total;
        }

        private function getDpTotalBetween(string $start, string $end): int
        {
            // Sum of first payment per invoice inside the range (by service in range)
            $result = 0;

            $transaksiList = TransaksiService::whereBetween('tanggal_service', [$start, $end])
                ->where('status_pembayaran', '!=', 'lunas')
                ->get(['id']);

            foreach ($transaksiList as $trx) {
                $firstPayment = ServicePayment::where('transaksi_service_id', $trx->id)
                    ->orderBy('tanggal_bayar')
                    ->orderBy('created_at')
                    ->first();

                if ($firstPayment) {
                    $result += (int)$firstPayment->jumlah_bayar;
                }
            }

            return $result;
        }

        private function summarizeRange(string $start, string $end): array
        {
            $rows = $this->getServiceTransactionsBetween($start, $end);

            $pendapatanKotor = (int)$rows->sum('jumlah');
            $totalDiscount   = (int)$rows->sum('discount');
            $totalModal      = (int)$rows->sum('modal');
            $totalJasa       = (int)$rows->sum('jasa');
            $totalLabaSpart  = (int)$rows->sum('laba_spart');
            $operasional     = $this->getOperationalTotalBetween($start, $end);
            $piutang         = $this->getPiutangTotalBetween($start, $end);
            $dp              = $this->getDpTotalBetween($start, $end);

            $pendapatanBersih = $pendapatanKotor - $totalModal - $totalDiscount - $operasional;

            return [
                'pendapatan_kotor'  => $pendapatanKotor,
                'operasional'       => $operasional,
                'jasa'              => $totalJasa,
                'laba_spart'        => $totalLabaSpart,
                'discount'          => $totalDiscount,
                'dp'                => $dp,
                'piutang'           => $piutang,
                'pendapatan_bersih' => $pendapatanBersih,
            ];
        }

        // --- Computed: weekly summaries inside current month ---
        public function getWeeklySummariesProperty(): Collection
        {
            $weeks = $this->getWeeksInMonth();
            $data = collect();

            foreach ($weeks as $week) {
                $summary = $this->summarizeRange($week['start'], $week['end']);
                $data->push(array_merge([
                    'week'  => $week['index'],
                    'start' => $week['start'],
                    'end'   => $week['end'],
                ], $summary));
            }

            return $data;
        }

        public function getMonthlyTotalsProperty(): array
        {
            $w = $this->weeklySummaries;

            $totalPendapatanKotor = (int)$w->sum('pendapatan_kotor');
            $totalOperasional     = (int)$w->sum('operasional');
            $totalJasa            = (int)$w->sum('jasa');
            $totalLabaSpart       = (int)$w->sum('laba_spart');
            $totalDiscount        = (int)$w->sum('discount');
            $totalDp              = (int)$w->sum('dp');
            $totalPiutang         = (int)$w->sum('piutang');

            return [
                'total_pendapatan_kotor'  => $totalPendapatanKotor,
                'total_operasional'       => $totalOperasional,
                'total_jasa'              => $totalJasa,
                'total_laba_spart'        => $totalLabaSpart,
                'total_discount'          => $totalDiscount,
                'total_dp'                => $totalDp,
                'total_piutang'           => $totalPiutang,
                'total_piutang_dp'        => max(0, $totalPiutang - $totalDp),
                'total_pendapatan_bersih' => (int)$w->sum('pendapatan_bersih'),
            ];
        }

        // --- Piutang detail list for the month ---
        public function getPiutangDetailProperty(): Collection
        {
            $start = $this->startDate;
            $end   = $this->endDate;

            $all = TransaksiService::whereBetween('tanggal_service', [$start, $end])
                ->with('pelangganMobil')
                ->orderBy('tanggal_service')
                ->get();

            $rows = [];

            foreach ($all as $idx => $trx) {
                $subtotal = (int)$trx->total_barang + (int)$trx->total_jasa;
                $discount = 0;
                if ($trx->diskon > 0) {
                    $discount = $trx->tipe_diskon === 'persentase'
                        ? (int) round(($subtotal * $trx->diskon) / 100)
                        : (int)$trx->diskon;
                }
                $grandTotal = max(0, $subtotal - $discount);

                // DP diambil dari pembayaran pertama
                $firstPayment = ServicePayment::where('transaksi_service_id', $trx->id)
                    ->orderBy('tanggal_bayar')
                    ->orderBy('created_at')
                    ->first();
                $dpPertama = $firstPayment ? (int)$firstPayment->jumlah_bayar : 0;

                $initialPiutang = max(0, $grandTotal - $dpPertama);
                if ($initialPiutang <= 0) {
                    continue;
                }

                $isLunas = $trx->status_pembayaran === 'lunas';
                $tanggalLunas = null;
                if ($isLunas) {
                    $lastPayment = ServicePayment::where('transaksi_service_id', $trx->id)
                        ->orderByDesc('tanggal_bayar')
                        ->orderByDesc('created_at')
                        ->first();
                    $tanggalLunas = $lastPayment ? \Carbon\Carbon::parse($lastPayment->tanggal_bayar)->format('Y-m-d') : null;
                }

                $rows[] = [
                    'no'             => count($rows) + 1,
                    'tanggal'        => \Carbon\Carbon::parse($trx->tanggal_service)->format('d-M-y'),
                    'invoice'        => $trx->invoice,
                    'merk_nopol'     => strtoupper(($trx->pelangganMobil->merk_mobil ?? 'N/A')) . ' / ' . strtoupper(($trx->pelangganMobil->nopol ?? 'N/A')),
                    'laporan'        => 'MINGGU KE-' . $this->getWeekNumberInMonth(\Carbon\Carbon::parse($trx->tanggal_service)) . ' ' . strtoupper(\Carbon\Carbon::parse($trx->tanggal_service)->translatedFormat('F Y')),
                    'tagihan'        => $initialPiutang,            // utang - DP pertama
                    'sisa'           => (int)$trx->sisa_pembayaran, // untuk footer "SISA TAGIHAN"
                    'keterangan'     => '-',                        // teks bebas bila diperlukan
                    'lunas'          => $isLunas,                   // for Blade conditional
                    'tanggal_lunas'  => $tanggalLunas,              // string Y-m-d (Blade akan format)
                ];
            }

            return collect($rows);
        }

        private function getWeekNumberInMonth(Carbon $date): int
        {
            $startDate = $date->copy()->startOfWeek(Carbon::MONDAY);
            $monthStart = $date->copy()->startOfMonth();

            // first Monday on/after monthStart
            $m = $monthStart->copy();
            while ($m->dayOfWeek !== Carbon::MONDAY) {
                $m->addDay();
            }

            $weekNumber = 1;
            while ($m->lt($startDate)) {
                $m->addWeek();
                $weekNumber++;
            }
            return max(1, $weekNumber);
        }

        public function render()
        {
            return view('livewire.laporan-bulanan', [
                'weeklySummaries' => $this->weeklySummaries,
                'monthlyTotals'   => $this->monthlyTotals,
                'piutangDetail'   => $this->piutangDetail,
            ]);
        }

        public function exportBulanan()
        {
            $weeklySummaries = $this->weeklySummaries->toArray();
            $monthlyTotals = $this->monthlyTotals;
            
            $monthName = Carbon::create($this->year, $this->month, 1)->translatedFormat('F');
            $filename = 'laporan-bulanan-' . strtolower($monthName) . '-' . $this->year . '.xlsx';
            
            return Excel::download(new LaporanBulananExport($weeklySummaries, $monthlyTotals, [
                'month' => $this->month,
                'year' => $this->year,
            ]), $filename);
        }

        public function exportPiutang()
        {
            $piutangDetail = $this->piutangDetail->toArray();
            $monthName = Carbon::create($this->year, $this->month, 1)->translatedFormat('F');
            $filename = 'laporan-piutang-' . strtolower($monthName) . '-' . $this->year . '.xlsx';
            
            return Excel::download(new LaporanPiutangExport($piutangDetail, [
                'month' => $this->month,
                'year' => $this->year,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
            ]), $filename);
        }
    }
