<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class TransaksiBarang extends Component
{
    public $kasir;
    public $keterangan;
    
    // Regular item properties
    public $barang_id;
    public $jumlah;
    public $harga_jual;
    public $selectedBarangInfo = null;
    
    // Manual item properties
    public $nama_barang_manual = '';
    public $jumlah_manual = 1;
    public $satuan_manual = 'pcs';
    public $harga_jual_manual = 0;
    public $harga_beli_manual = 0;
    public $keterangan_manual = '';
    
    public $items = [];
    public $tanggal_pembelian;

    protected $rules = [
        'kasir' => 'required|string|max:100',
        'tanggal_pembelian' => 'required|date',
        'barang_id' => 'required|exists:barangs,id',
        'jumlah' => 'required|integer|min:1',
        'harga_jual' => 'required|numeric|min:0',
        
        // Manual item rules
        'nama_barang_manual' => 'required|string|max:255',
        'jumlah_manual' => 'required|integer|min:1',
        'satuan_manual' => 'required|string|max:20',
        'harga_jual_manual' => 'required|numeric|min:0',
        'harga_beli_manual' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'kasir.required' => 'Nama kasir wajib diisi.',
        'tanggal_pembelian.required' => 'Tanggal transaksi wajib diisi.',
        'barang_id.required' => 'Pilih barang terlebih dahulu.',
        'barang_id.exists' => 'Barang tidak ditemukan.',
        'jumlah.required' => 'Jumlah wajib diisi.',
        'jumlah.min' => 'Jumlah minimal 1.',
        'harga_jual.required' => 'Harga jual wajib diisi.',
        'harga_jual.min' => 'Harga jual tidak boleh negatif.',
        
        // Manual item messages
        'nama_barang_manual.required' => 'Nama barang wajib diisi.',
        'nama_barang_manual.max' => 'Nama barang maksimal 255 karakter.',
        'jumlah_manual.required' => 'Jumlah barang wajib diisi.',
        'jumlah_manual.min' => 'Jumlah minimal 1.',
        'satuan_manual.required' => 'Satuan wajib dipilih.',
        'harga_jual_manual.required' => 'Harga jual wajib diisi.',
        'harga_jual_manual.min' => 'Harga jual tidak boleh negatif.',
        'harga_beli_manual.required' => 'Harga beli wajib diisi.',
        'harga_beli_manual.min' => 'Harga beli tidak boleh negatif.',
    ];

    public function mount()
    {
        $this->kasir = Auth::user()->name ?? 'Admin';
        $this->tanggal_pembelian = now()->timezone('Asia/Makassar')->format('Y-m-d');
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
                $query->orderBy('tanggal');
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
        
        $totalStokTersedia = Pembelian::where('barang_id', $this->barang_id)
            ->where('jumlah_tersisa', '>', 0)
            ->sum('jumlah_tersisa');

        if ($totalStokTersedia < $this->jumlah || $totalStokTersedia <= 0) {
            $this->addError('jumlah', 'Stok tidak mencukupi. Stok tersedia: ' . $totalStokTersedia);
            return;
        }

        $existingIndex = collect($this->items)->search(function($item) {
            return isset($item['barang_id']) && $item['barang_id'] == $this->barang_id && 
                   (!isset($item['is_manual']) || !$item['is_manual']);
        });

        if ($existingIndex !== false) {
            $totalJumlah = $this->items[$existingIndex]['jumlah'] + $this->jumlah;
            
            if ($totalStokTersedia < $totalJumlah) {
                $this->addError('jumlah', 'Total jumlah melebihi stok. Stok tersedia: ' . $totalStokTersedia);
                return;
            }
            
            $this->items[$existingIndex]['jumlah'] = $totalJumlah;
            $this->items[$existingIndex]['harga_jual'] = $this->harga_jual;
            $this->items[$existingIndex]['subtotal'] = $totalJumlah * $this->harga_jual;
        } else {
            $this->items[] = [
                'barang_id' => $barang->id,
                'nama' => $barang->nama,
                'jumlah' => $this->jumlah,
                'harga_jual' => $this->harga_jual,
                'subtotal' => $this->jumlah * $this->harga_jual,
                'stok_tersedia' => $totalStokTersedia,
                'supplier_info' => $this->selectedBarangInfo['suppliers'] ?? [],
                'tanggal' => $this->tanggal_pembelian,
                'is_manual' => false,
            ];
        }

        $this->resetFormInputs();
        $this->resetErrorBag();
        $this->dispatch('item-added');
        
        Log::info('Regular item added successfully', [
            'nama' => $barang->nama,
            'jumlah' => $this->jumlah,
            'total_items' => count($this->items)
        ]);
    }

    public function tambahBarangManual()
    {
        $this->validate([
            'nama_barang_manual' => 'required|string|max:255',
            'jumlah_manual' => 'required|integer|min:1',
            'satuan_manual' => 'required|string|max:20',
            'harga_jual_manual' => 'required|numeric|min:0',
            'harga_beli_manual' => 'required|numeric|min:0',
        ]);

        try {
            $namaBarang = $this->nama_barang_manual;
            $jumlahBarang = $this->jumlah_manual;
            $satuanBarang = $this->satuan_manual;
            $hargaJual = $this->harga_jual_manual;
            $hargaBeli = $this->harga_beli_manual;
            $keterangan = $this->keterangan_manual ?? '';
            
            $tempId = 'manual_' . uniqid() . '_' . time();
            
            $existingIndex = collect($this->items)->search(function($item) use ($namaBarang) {
                return isset($item['is_manual']) && $item['is_manual'] && 
                    strtolower($item['nama']) === strtolower($namaBarang);
            });

            if ($existingIndex !== false) {
                $this->items[$existingIndex]['jumlah'] += $jumlahBarang;
                $this->items[$existingIndex]['harga_jual'] = $hargaJual;
                $this->items[$existingIndex]['harga_beli_manual'] = $hargaBeli;
                $this->items[$existingIndex]['subtotal'] = $this->items[$existingIndex]['jumlah'] * $hargaJual;
                $this->items[$existingIndex]['keterangan'] = $keterangan;
                
                $message = 'Barang manual berhasil diperbarui!';
            } else {
                $this->items[] = [
                    'barang_id' => $tempId,
                    'nama' => $namaBarang,
                    'jumlah' => $jumlahBarang,
                    'satuan' => $satuanBarang,
                    'harga_jual' => $hargaJual,
                    'harga_beli_manual' => $hargaBeli,
                    'subtotal' => $jumlahBarang * $hargaJual,
                    'keterangan' => $keterangan,
                    'is_manual' => true,
                    'stok_tersedia' => 'MANUAL',
                    'tanggal' => $this->tanggal_pembelian,
                ];
                
                $message = 'Barang manual berhasil ditambahkan!';
            }

            Log::info('Manual item added successfully', [
                'nama' => $namaBarang,
                'jumlah' => $jumlahBarang,
                'total_items' => count($this->items)
            ]);

            $this->resetManualInputs();
            session()->flash('success', $message);
            
            $this->dispatch('manual-item-added');
            $this->dispatch('hide-manual-form');
            
        } catch (\Exception $e) {
            Log::error('Error adding manual item: ' . $e->getMessage());
            $errorMessage = 'Terjadi kesalahan saat menambah barang manual: ' . $e->getMessage();
            $this->addError('general', $errorMessage);
            $this->dispatch('manual-item-error', $errorMessage);
        }
    }

    public function resetManualInputs()
    {
        $this->reset([
            'nama_barang_manual', 'jumlah_manual', 'satuan_manual',
            'harga_jual_manual', 'harga_beli_manual', 'keterangan_manual'
        ]);
        
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->harga_beli_manual = 0;
        $this->nama_barang_manual = '';
        $this->keterangan_manual = '';
        
        $this->resetValidation([
            'nama_barang_manual', 'jumlah_manual', 'satuan_manual', 
            'harga_jual_manual', 'harga_beli_manual', 'keterangan_manual'
        ]);
        
        $this->dispatch('manual-form-reset');
    }

    public function forceResetManualInputs()
    {
        $this->nama_barang_manual = '';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->harga_beli_manual = 0;
        $this->keterangan_manual = '';
        
        $this->resetErrorBag();
        $this->dispatch('manual-form-reset');
    }

    public function resetFormInputs()
    {
        $this->barang_id = '';
        $this->jumlah = '';
        $this->harga_jual = '';
        $this->selectedBarangInfo = null;
        $this->resetErrorBag(['barang_id', 'jumlah', 'harga_jual']);
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
        return collect($this->items)->sum('subtotal');
    }

    public function simpanPenjualan()
    {
        $this->validate([
            'kasir' => 'required|string|max:100',
            'tanggal_pembelian' => 'required|date', 
        ]);

        if (count($this->items) === 0) {
            $this->addError('general', 'Minimal 1 barang harus ditambahkan.');
            return;
        }

        foreach ($this->items as $index => $item) {
            if (!is_array($item)) { $this->addError('general', "Item ke-{$index} tidak valid"); return; }
            if (!isset($item['nama']) || empty($item['nama'])) { $this->addError('general', "Nama barang ke-{$index} tidak boleh kosong"); return; }
            if (!isset($item['jumlah']) || $item['jumlah'] <= 0) { $this->addError('general', "Jumlah barang '{$item['nama']}' harus lebih dari 0"); return; }
            if (!isset($item['harga_jual']) || $item['harga_jual'] < 0) { $this->addError('general', "Harga jual barang '{$item['nama']}' tidak valid"); return; }

            if (isset($item['is_manual']) && $item['is_manual']) {
                if (!isset($item['satuan']) || empty($item['satuan'])) { $item['satuan'] = 'pcs'; }
                if (!isset($item['harga_beli_manual']) || $item['harga_beli_manual'] < 0) { $this->addError('general', "Harga beli barang manual '{$item['nama']}' tidak valid"); return; }
            } else {
                if (!isset($item['barang_id']) || empty($item['barang_id'])) { $this->addError('general', "ID barang untuk '{$item['nama']}' tidak valid"); return; }
                $availableStock = Pembelian::where('barang_id', $item['barang_id'])->where('jumlah_tersisa', '>', 0)->sum('jumlah_tersisa');
                if ($item['jumlah'] > $availableStock) { $this->addError('general', "Stok {$item['nama']} tidak mencukupi. Tersedia: {$availableStock}"); return; }
            }
        }

        $tanggalFinal = Carbon::parse($this->tanggal_pembelian)->timezone('Asia/Makassar');
        $waktuSekarang = now()->timezone('Asia/Makassar');
        $tanggalFinal->setTimeFrom($waktuSekarang);

        DB::beginTransaction();
        try {
            $totalHarga = $this->getTotalHarga();
            
            // PENGATURAN BARU: Masukkan field 'tanggal' untuk memenuhi batasan database
            $penjualan = new Transaksi([
                'kasir' => $this->kasir,
                'keterangan' => $this->keterangan,
                'tanggal' => $tanggalFinal->toDateString(),
                'total_harga' => $totalHarga,
            ]);

            $penjualan->created_at = $tanggalFinal;
            $penjualan->updated_at = $tanggalFinal;
            $penjualan->save();

            Log::info('Penjualan created via created_at override', ['id' => $penjualan->id, 'total' => $totalHarga]);

            foreach ($this->items as $index => $item) {
                try {
                    if (isset($item['is_manual']) && $item['is_manual']) {
                        $this->prosesItemManual($penjualan->id, $item);
                    } else {
                        $this->prosesItemPenjualan($penjualan->id, $item);
                    }
                } catch (\Exception $e) {
                    $itemName = $item['nama'] ?? "Item ke-{$index}";
                    throw new \Exception("Gagal memproses item: {$itemName} - {$e->getMessage()}");
                }
            }

            DB::commit();

            $this->showSuccessMessage($totalHarga);
            $this->resetAfterSale();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
            Log::error('Error in simpanPenjualan: ' . $e->getMessage());
        }
    }

    private function prosesItemManual($transaksiId, $item)
    {
        try {
            Log::info('Processing manual item', [
                'transaksi_id' => $transaksiId,
                'item' => $item
            ]);

            $data = [
                'penjualan_id' => $transaksiId,
                'barang_id' => null,
                'pembelian_id' => null,
                'nama_barang_manual' => $item['nama'],
                'jumlah' => $item['jumlah'],
                'satuan' => $item['satuan'] ?? 'pcs',
                'harga_jual' => $item['harga_jual'],
                'harga_beli_manual' => $item['harga_beli_manual'],
                'keterangan' => $item['keterangan'] ?? null,
                'is_manual' => true,
            ];

            Log::info('Creating PenjualanItem with data', $data);
            $penjualanItem = PenjualanItem::create($data);

            Log::info('Manual item created successfully', [
                'id' => $penjualanItem->id,
                'nama' => $item['nama']
            ]);

            return $penjualanItem;

        } catch (\Exception $e) {
            Log::error('Error in prosesItemManual', [
                'error' => $e->getMessage(),
                'item' => $item
            ]);
            throw $e;
        }
    }

    private function prosesItemPenjualan($transaksiId, $item)
    {
        $jumlahDibutuhkan = $item['jumlah'];
        $harga = $item['harga_jual'];
        $barang_id = $item['barang_id'];

        $pembelians = Pembelian::where('barang_id', $barang_id)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($pembelians as $pembelian) {
            if ($jumlahDibutuhkan <= 0) break;

            $jumlahDiambil = min($jumlahDibutuhkan, $pembelian->jumlah_tersisa);

            PenjualanItem::create([
                'penjualan_id' => $transaksiId,
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang_id,
                'nama_barang_manual' => null,
                'jumlah' => $jumlahDiambil,
                'harga_jual' => $harga,
                'is_manual' => false,
            ]);

            $pembelian->jumlah_tersisa -= $jumlahDiambil;
            $pembelian->save();

            $jumlahDibutuhkan -= $jumlahDiambil;
        }

        if ($jumlahDibutuhkan > 0) {
            throw new \Exception("Stok tidak mencukupi untuk barang ID: {$barang_id}");
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
        $this->reset([
            'keterangan', 'items', 'barang_id', 'jumlah', 'harga_jual', 'selectedBarangInfo',
            'nama_barang_manual', 'jumlah_manual', 'satuan_manual', 'harga_jual_manual', 
            'harga_beli_manual', 'keterangan_manual'
        ]);
        $this->kasir = Auth::user()->name ?? 'Admin';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        // Set ulang input kalender ke hari ini setelah data tersimpan
        $this->tanggal_pembelian = now()->timezone('Asia/Makassar')->format('Y-m-d');
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