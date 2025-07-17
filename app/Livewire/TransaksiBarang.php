<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class TransaksiBarang extends Component
{
    public $kasir;
    public $keterangan;
    public $barang_id;
    public $jumlah;
    public $harga_jual;
    public $items = [];
    public $selectedBarangInfo = null;

    protected $rules = [
        'kasir' => 'required|string|max:100',
        'barang_id' => 'required|exists:barangs,id',
        'jumlah' => 'required|integer|min:1',
        'harga_jual' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'kasir.required' => 'Nama kasir wajib diisi.',
        'barang_id.required' => 'Pilih barang terlebih dahulu.',
        'barang_id.exists' => 'Barang tidak ditemukan.',
        'jumlah.required' => 'Jumlah wajib diisi.',
        'jumlah.min' => 'Jumlah minimal 1.',
        'harga_jual.required' => 'Harga jual wajib diisi.',
        'harga_jual.min' => 'Harga jual tidak boleh negatif.',
    ];

    public function mount()
    {
        $this->kasir = Auth::user()->name ?? 'Admin';
    }

    public function render()
    {
        return view('livewire.transaksi-barang', [
            'barangs' => Barang::with(['pembelians' => function($query) {
                $query->where('jumlah_tersisa', '>', 0)->orderBy('tanggal');
            }])->get()
        ]);
    }

    public function updatedBarangId($value)
    {
        if ($value && $value !== '') {
            $barang = Barang::with(['pembelians' => function($query) {
                $query->orderBy('tanggal'); // Hapus where jumlah_tersisa > 0
            }])->find($value);
            
            if ($barang) {
                $this->selectedBarangInfo = [
                    'nama' => $barang->nama,
                    'total_stok' => $barang->pembelians->sum('jumlah_tersisa'),
                    'suppliers' => $barang->pembelians->groupBy('supplier')->map(function($items, $supplier) {
                        return [
                            'supplier' => $supplier,
                            'stok' => $items->sum('jumlah_tersisa'),
                            'harga_beli_rata' => $items->avg('harga_beli'),
                            'pembelians' => $items->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'tanggal' => $item->tanggal,
                                    'stok' => $item->jumlah_tersisa,
                                    'harga_beli' => $item->harga_beli
                                ];
                            })
                        ];
                    })->values()->toArray()
                ];
                
                // Auto-suggest price with 30% markup from average cost
                $avgHargaBeli = $barang->pembelians->avg('harga_beli');
                if ($avgHargaBeli && !$this->harga_jual) {
                    $this->harga_jual = round($avgHargaBeli * 1.2);
                }
            }
        } else {
            $this->selectedBarangInfo = null;
            $this->harga_jual = '';
        }
    }

    public function selectBarang($barangId)
    {
        $barang = Barang::find($barangId);
        $totalStok = $barang->pembelians->where('jumlah_tersisa', '>', 0)->sum('jumlah_tersisa');
        
        if ($totalStok <= 0) {
            $this->addError('general', 'Item ini sedang tidak tersedia (stok habis)');
            return;
        }
        
        $this->barang_id = $barangId;
        $this->updatedBarangId($barangId);
        $this->resetErrorBag(['general']);
    }

    public function tambahItem()
    {
        $this->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barang = Barang::find($this->barang_id);
        
        // Check available stock from all suppliers
        $totalStokTersedia = Pembelian::where('barang_id', $this->barang_id)
            ->where('jumlah_tersisa', '>', 0)
            ->sum('jumlah_tersisa');

        if ($totalStokTersedia < $this->jumlah || $totalStokTersedia <= 0) {
            $this->addError('jumlah', 'Stok tidak mencukupi. Stok tersedia: ' . $totalStokTersedia);
            return;
        }

        // Check if item already exists in cart
        $existingIndex = collect($this->items)->search(function($item) {
            return $item['barang_id'] == $this->barang_id;
        });

        if ($existingIndex !== false) {
            // Update quantity if item already exists
            $totalJumlah = $this->items[$existingIndex]['jumlah'] + $this->jumlah;
            
            if ($totalStokTersedia < $totalJumlah) {
                $this->addError('jumlah', 'Total jumlah melebihi stok. Stok tersedia: ' . $totalStokTersedia);
                return;
            }
            
            $this->items[$existingIndex]['jumlah'] = $totalJumlah;
            $this->items[$existingIndex]['harga_jual'] = $this->harga_jual; // Update price too
        } else {
            // Add new item
            $this->items[] = [
                'barang_id' => $barang->id,
                'nama' => $barang->nama,
                'jumlah' => $this->jumlah,
                'harga_jual' => $this->harga_jual,
                'stok_tersedia' => $totalStokTersedia,
                'supplier_info' => $this->selectedBarangInfo['suppliers'] ?? [],
                'tanggal' => now()->toDateString(),
            ];
        }

        $this->resetFormInputs();
        $this->resetErrorBag();
        $this->dispatch('item-added');
    }

    public function resetFormInputs()
    {
        $this->barang_id = '';
        $this->jumlah = '';
        $this->harga_jual = '';
        $this->selectedBarangInfo = null;
        $this->dispatch('form-reset');
    }

    public function hapusItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function getTotalHarga()
    {
        return collect($this->items)->sum(fn($item) => $item['jumlah'] * $item['harga_jual']);
    }

    public function simpanPenjualan()
    {
        $this->validate([
            'kasir' => 'required|string|max:100',
        ]);

        if (count($this->items) === 0) {
            $this->addError('general', 'Minimal 1 barang harus ditambahkan.');
            return;
        }

        DB::beginTransaction();
        try {
            $totalHarga = $this->getTotalHarga();
            
            // Create main transaction record
            $penjualan = Transaksi::create([
                'kasir' => $this->kasir,
                'keterangan' => $this->keterangan,
                'tanggal' => now()->toDateString(),
                'total_harga' => $totalHarga, // Add total_harga here
            ]);

            foreach ($this->items as $item) {
                $this->prosesItemPenjualan($penjualan->id, $item);
            }

            DB::commit();

            $this->showSuccessMessage($totalHarga);
            $this->resetAfterSale();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Terjadi kesalahan: ' . $e->getMessage());
            \Log::error('Error in simpanPenjualan: ' . $e->getMessage());
        }
    }

    private function prosesItemPenjualan($penjualanId, $item)
    {
        $jumlahDibutuhkan = $item['jumlah'];
        $harga = $item['harga_jual'];
        $barang_id = $item['barang_id']; // ✅ Pastikan ini ada!

        // FIFO: Ambil dari stok terlama dulu
        $pembelians = Pembelian::where('barang_id', $barang_id)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($pembelians as $pembelian) {
            if ($jumlahDibutuhkan <= 0) break;

            $jumlahDiambil = min($jumlahDibutuhkan, $pembelian->jumlah_tersisa);

            // Buat record item penjualan
            PenjualanItem::create([
                'penjualan_id' => $penjualanId,
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang_id, // ✅ Pastikan ini terisi
                'jumlah' => $jumlahDiambil,
                'harga_jual' => $harga,
            ]);

            // Kurangi stok tersedia
            $pembelian->jumlah_tersisa -= $jumlahDiambil;
            $pembelian->save();

            $jumlahDibutuhkan -= $jumlahDiambil;
        }
    }

    private function showSuccessMessage($totalHarga)
    {
        session()->flash('message', 
            'Penjualan berhasil disimpan dengan total Rp ' . number_format($totalHarga, 0, ',', '.')
        );
    }

    private function resetAfterSale()
    {
        $this->reset(['keterangan', 'items', 'barang_id', 'jumlah', 'harga_jual', 'selectedBarangInfo']);
        $this->kasir = Auth::user()->name ?? 'Admin';
    }

    public function getStokDetailBySupplier($barang_id)
    {
        return Pembelian::where('barang_id', $barang_id)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal')
            ->get()
            ->groupBy('supplier')
            ->map(function($items, $supplier) {
                return [
                    'supplier' => $supplier,
                    'total_stok' => $items->sum('jumlah_tersisa'),
                    'pembelians' => $items->map(function($item) {
                        return [
                            'tanggal' => $item->tanggal,
                            'stok' => $item->jumlah_tersisa,
                            'harga_beli' => $item->harga_beli
                        ];
                    })
                ];
            });
    }
}