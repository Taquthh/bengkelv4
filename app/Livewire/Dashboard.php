<?php

namespace App\Livewire;

use App\Models\TransaksiService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends RiwayatService
{
    public int $limit = 10;

    public function mount(): void
    {
        parent::mount();
    }

    public function getRecentTransactionsProperty()
    {
        return TransaksiService::with('pelangganMobil')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();
    }

    public function getDashboardStatsProperty(): array
    {
        $bulanIni = Carbon::now()->startOfMonth();

        return [
            'total'       => TransaksiService::whereDate('created_at', '>=', $bulanIni)->count(),
            'belum_lunas' => TransaksiService::whereIn('status_pembayaran', ['belum', 'sebagian'])->count(),
            'selesai'     => TransaksiService::where('status_pekerjaan', 'selesai')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'recentTransactions' => $this->recentTransactions,
            'dashboardStats'     => $this->dashboardStats,
            'limit'              => $this->limit,
        ]);
    }
}