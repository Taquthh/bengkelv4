<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi;
use App\Models\PenjualanItem;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class RiwayatTransaksiBarang extends Component
{
    use WithPagination;

    public $search = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $kasir_filter = '';
    public $per_page = 10;
    public $sortBy = 'tanggal';
    public $sortDirection = 'desc';
    
    // Detail modal
    public $showDetailModal = false;
    public $selectedTransaksi = null;
    public $detailItems = [];
    
    // Summary data
    public $totalPenjualan = 0;
    public $totalTransaksi = 0;
    public $totalProfit = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'tanggal_mulai' => ['except' => ''],
        'tanggal_selesai' => ['except' => ''],
        'kasir_filter' => ['except' => ''],
        'per_page' => ['except' => 10],
        'page' => ['except' => 1],
    ];

    public function updatingPage()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Set default tanggal ke bulan ini
        $this->tanggal_mulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_selesai = now()->format('Y-m-d');
        $this->hitungSummary();
    }

    public function render()
    {
        $transaksis = $this->getTransaksiData();
        $kasirList = $this->getKasirList();
        
        return view('livewire.riwayat-transaksi-barang', [
            'transaksis' => $transaksis,
            'kasirList' => $kasirList,
        ]);
    }

    public function getTransaksiData()
    {
        $query = Transaksi::with(['itemPenjualan.barang', 'itemPenjualan.pembelian'])
            ->select('*');

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('kasir', 'like', '%' . $this->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('itemPenjualan.barang', function($subQ) {
                      $subQ->where('nama', 'like', '%' . $this->search . '%')
                           ->orWhere('merk', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('itemPenjualan', function($subQ) {
                      $subQ->where('nama_barang_manual', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter tanggal
        if ($this->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $this->tanggal_mulai);
        }
        if ($this->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $this->tanggal_selesai);
        }

        // Filter kasir
        if ($this->kasir_filter) {
            $query->where('kasir', $this->kasir_filter);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->per_page);
    }

    public function getKasirList()
    {
        return Transaksi::select('kasir')
            ->distinct()
            ->orderBy('kasir')
            ->pluck('kasir');
    }

    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->hitungSummary();
    }

    public function updatedTanggalMulai()
    {
        $this->resetPage();
        $this->hitungSummary();
    }

    public function updatedTanggalSelesai()
    {
        $this->resetPage();
        $this->hitungSummary();
    }

    public function updatedKasirFilter()
    {
        $this->resetPage();
        $this->hitungSummary();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['search', 'tanggal_mulai', 'tanggal_selesai', 'kasir_filter']);
        $this->tanggal_mulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_selesai = now()->format('Y-m-d');
        $this->resetPage();
        $this->hitungSummary();
    }

    public function setTanggalHariIni()
    {
        $this->tanggal_mulai = now()->format('Y-m-d');
        $this->tanggal_selesai = now()->format('Y-m-d');
        $this->resetPage();
        $this->hitungSummary();
    }

    public function setTanggalMingguIni()
    {
        $this->tanggal_mulai = now()->startOfWeek()->format('Y-m-d');
        $this->tanggal_selesai = now()->endOfWeek()->format('Y-m-d');
        $this->resetPage();
        $this->hitungSummary();
    }

    public function setTanggalBulanIni()
    {
        $this->tanggal_mulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_selesai = now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
        $this->hitungSummary();
    }

    public function lihatDetail($transaksiId)
    {
        $this->selectedTransaksi = Transaksi::with(['itemPenjualan.barang', 'itemPenjualan.pembelian'])
            ->find($transaksiId);
        
        if ($this->selectedTransaksi) {
            $this->detailItems = $this->selectedTransaksi->itemPenjualan->map(function($item) {
                // Check if this is a manual item
                if ($item->is_manual || $item->nama_barang_manual) {
                    return [
                        'barang_nama' => $item->nama_barang_manual,
                        'jumlah' => $item->jumlah,
                        'satuan' => $item->satuan ?? 'pcs',
                        'harga_jual' => $item->harga_jual,
                        'subtotal' => $item->subtotal,
                        'harga_beli' => $item->harga_beli_manual ?? 0,
                        'profit' => $this->hitungProfitManual($item),
                        'profit_margin' => $this->hitungProfitMarginManual($item),
                        'supplier' => 'MANUAL',
                        'is_manual' => true,
                        'keterangan' => $item->keterangan ?? '',
                    ];
                } else {
                    // Regular item
                    return [
                        'barang_nama' => $item->barang->nama_lengkap ?? $item->barang->nama ?? 'N/A',
                        'jumlah' => $item->jumlah,
                        'satuan' => $item->barang->satuan ?? 'pcs',
                        'harga_jual' => $item->harga_jual,
                        'subtotal' => $item->subtotal,
                        'harga_beli' => $item->pembelian->harga_beli ?? 0,
                        'profit' => $item->profit,
                        'profit_margin' => $item->profit_margin,
                        'supplier' => $item->pembelian->supplier ?? '-',
                        'is_manual' => false,
                        'keterangan' => '',
                    ];
                }
            })->toArray();
            
            $this->showDetailModal = true;
        }
    }

    private function hitungProfitManual($item)
    {
        $hargaBeli = $item->harga_beli_manual ?? 0;
        return ($item->harga_jual - $hargaBeli) * $item->jumlah;
    }

    private function hitungProfitMarginManual($item)
    {
        $hargaBeli = $item->harga_beli_manual ?? 0;
        
        if ($hargaBeli <= 0) {
            return 100; // 100% profit if no cost
        }
        
        $profitPerUnit = $item->harga_jual - $hargaBeli;
        return round(($profitPerUnit / $hargaBeli) * 100, 2);
    }

    public function tutupDetail()
    {
        $this->showDetailModal = false;
        $this->selectedTransaksi = null;
        $this->detailItems = [];
    }

    public function hitungSummary()
    {
        $query = Transaksi::query();

        // Apply same filters as main query
        if ($this->search) {
            $query->where(function($q) {
                $q->where('kasir', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%')
                ->orWhere('id', 'like', '%' . $this->search . '%')
                ->orWhereHas('itemPenjualan.barang', function($subQ) {
                    $subQ->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('merk', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('itemPenjualan', function($subQ) {
                    $subQ->where('nama_barang_manual', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $this->tanggal_mulai);
        }
        if ($this->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $this->tanggal_selesai);
        }
        if ($this->kasir_filter) {
            $query->where('kasir', $this->kasir_filter);
        }

        // Hitung summary
        $this->totalTransaksi = $query->count();
        $this->totalPenjualan = $query->sum('total_harga');
        
        // Hitung total profit untuk regular dan manual items
        $transaksiIds = $query->pluck('id');
        
        if ($transaksiIds->isNotEmpty()) {
            // Profit dari regular items
            $regularProfit = DB::table('penjualan_items as pi')
                ->join('pembelians as p', 'pi.pembelian_id', '=', 'p.id')
                ->whereIn('pi.penjualan_id', $transaksiIds)
                ->where('pi.is_manual', false)
                ->selectRaw('SUM((pi.harga_jual - p.harga_beli) * pi.jumlah) as total_profit')
                ->value('total_profit') ?? 0;

            // Profit dari manual items
            $manualProfit = DB::table('penjualan_items as pi')
                ->whereIn('pi.penjualan_id', $transaksiIds)
                ->where('pi.is_manual', true)
                ->selectRaw('SUM((pi.harga_jual - COALESCE(pi.harga_beli_manual, 0)) * pi.jumlah) as total_profit')
                ->value('total_profit') ?? 0;

            $this->totalProfit = $regularProfit + $manualProfit;
        } else {
            $this->totalProfit = 0;
        }
    }

    public function hapusTransaksiLangsung($transaksiId)
    {
        try {
            DB::beginTransaction();
            
            $transaksi = Transaksi::with(['itemPenjualan.pembelian'])->find($transaksiId);
            
            if (!$transaksi) {
                session()->flash('error', 'Transaksi tidak ditemukan.');
                DB::rollBack();
                return;
            }

            // Kembalikan stok ke pembelian (hanya untuk regular items, bukan manual items)
            foreach ($transaksi->itemPenjualan as $item) {
                if (!$item->is_manual && $item->pembelian) {
                    $item->pembelian->jumlah_tersisa += $item->jumlah;
                    $item->pembelian->save();
                }
                // Manual items tidak perlu dikembalikan ke stok
            }

            // Hapus item penjualan
            $transaksi->itemPenjualan()->delete();
            
            // Hapus transaksi
            $transaksi->delete();

            DB::commit();

            session()->flash('message', 'Transaksi #' . $transaksiId . ' berhasil dihapus dan stok dikembalikan.');
            
            // Force refresh semua data
            $this->hitungSummary();
            $this->resetPage();
            
            // Emit refresh event
            $this->dispatch('$refresh');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
            \Log::error('Delete transaction error: ' . $e->getMessage());
        }
    }

    // Method untuk refresh manual
    public function refreshComponent()
    {
        $this->hitungSummary();
        $this->dispatch('$refresh');
    }

    public function konfirmasiHapus($transaksiId)
    {
        $this->dispatch('confirm-delete', 
            transaksiId: $transaksiId,
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus transaksi ini? Stok barang reguler akan dikembalikan.'
        );
    }
}