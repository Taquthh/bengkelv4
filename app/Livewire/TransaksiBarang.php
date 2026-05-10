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

    protected $rules = [
        'kasir' => 'required|string|max:100',
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
                
                // Auto-suggest price with 20% markup from average cost
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
            return isset($item['barang_id']) && $item['barang_id'] == $this->barang_id && 
                   (!isset($item['is_manual']) || !$item['is_manual']);
        });

        if ($existingIndex !== false) {
            // Update quantity if item already exists
            $totalJumlah = $this->items[$existingIndex]['jumlah'] + $this->jumlah;
            
            if ($totalStokTersedia < $totalJumlah) {
                $this->addError('jumlah', 'Total jumlah melebihi stok. Stok tersedia: ' . $totalStokTersedia);
                return;
            }
            
            $this->items[$existingIndex]['jumlah'] = $totalJumlah;
            $this->items[$existingIndex]['harga_jual'] = $this->harga_jual;
            $this->items[$existingIndex]['subtotal'] = $totalJumlah * $this->harga_jual;
        } else {
            // Add new item
            $this->items[] = [
                'barang_id' => $barang->id,
                'nama' => $barang->nama,
                'jumlah' => $this->jumlah,
                'harga_jual' => $this->harga_jual,
                'subtotal' => $this->jumlah * $this->harga_jual,
                'stok_tersedia' => $totalStokTersedia,
                'supplier_info' => $this->selectedBarangInfo['suppliers'] ?? [],
                'tanggal' => now()->toDateString(),
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
            // SIMPAN data ke variable lokal SEBELUM reset
            $namaBarang = $this->nama_barang_manual;
            $jumlahBarang = $this->jumlah_manual;
            $satuanBarang = $this->satuan_manual;
            $hargaJual = $this->harga_jual_manual;
            $hargaBeli = $this->harga_beli_manual;
            $keterangan = $this->keterangan_manual ?? '';
            
            // Generate unique temporary ID for manual items
            $tempId = 'manual_' . uniqid() . '_' . time();
            
            // Check if manual item with same name already exists
            $existingIndex = collect($this->items)->search(function($item) use ($namaBarang) {
                return isset($item['is_manual']) && $item['is_manual'] && 
                    strtolower($item['nama']) === strtolower($namaBarang);
            });

            if ($existingIndex !== false) {
                // Update existing manual item
                $this->items[$existingIndex]['jumlah'] += $jumlahBarang;
                $this->items[$existingIndex]['harga_jual'] = $hargaJual;
                $this->items[$existingIndex]['harga_beli_manual'] = $hargaBeli;
                $this->items[$existingIndex]['subtotal'] = $this->items[$existingIndex]['jumlah'] * $hargaJual;
                $this->items[$existingIndex]['keterangan'] = $keterangan;
                
                $message = 'Barang manual berhasil diperbarui!';
            } else {
                // Add new manual item
                $this->items[] = [
                    'barang_id' => $tempId, // Temporary ID
                    'nama' => $namaBarang,
                    'jumlah' => $jumlahBarang,
                    'satuan' => $satuanBarang,
                    'harga_jual' => $hargaJual,
                    'harga_beli_manual' => $hargaBeli,
                    'subtotal' => $jumlahBarang * $hargaJual,
                    'keterangan' => $keterangan,
                    'is_manual' => true,
                    'stok_tersedia' => 'MANUAL',
                    'tanggal' => now()->toDateString(),
                ];
                
                $message = 'Barang manual berhasil ditambahkan!';
            }

            // Log SEBELUM reset (menggunakan variable lokal)
            Log::info('Manual item added successfully', [
                'nama' => $namaBarang,
                'jumlah' => $jumlahBarang,
                'total_items' => count($this->items)
            ]);

            // Reset form SETELAH data tersimpan
            $this->resetManualInputs();
            
            // Set flash message
            session()->flash('success', $message);
            
            // Emit success events
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
        Log::info('resetManualInputs called - Before reset', [
            'nama' => $this->nama_barang_manual,
            'jumlah' => $this->jumlah_manual,
            'satuan' => $this->satuan_manual
        ]);
        
        // Method 1: Reset menggunakan reset() method
        $this->reset([
            'nama_barang_manual',
            'jumlah_manual', 
            'satuan_manual',
            'harga_jual_manual',
            'harga_beli_manual',
            'keterangan_manual'
        ]);
        
        // Set default values setelah reset
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->harga_beli_manual = 0;
        $this->nama_barang_manual = '';
        $this->keterangan_manual = '';
        
        // Clear validation errors
        $this->resetValidation([
            'nama_barang_manual', 
            'jumlah_manual', 
            'satuan_manual', 
            'harga_jual_manual', 
            'harga_beli_manual', 
            'keterangan_manual'
        ]);
        
        Log::info('resetManualInputs completed - After reset', [
            'nama' => $this->nama_barang_manual,
            'jumlah' => $this->jumlah_manual,
            'satuan' => $this->satuan_manual
        ]);
        
        // Emit event untuk JavaScript
        $this->dispatch('manual-form-reset');
    }

    // Method alternatif untuk force reset
    public function forceResetManualInputs()
    {
        // Hard reset semua property
        $this->nama_barang_manual = '';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->harga_beli_manual = 0;
        $this->keterangan_manual = '';
        
        // Clear all errors
        $this->resetErrorBag();
        
        // Force re-render
        $this->dispatch('manual-form-reset');
        
        Log::info('Force reset manual inputs completed');
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
        ]);

        if (count($this->items) === 0) {
            $this->addError('general', 'Minimal 1 barang harus ditambahkan.');
            return;
        }

        // Validate items data integrity
        foreach ($this->items as $index => $item) {
            if (!is_array($item)) {
                $this->addError('general', "Item ke-{$index} tidak valid");
                return;
            }

            if (!isset($item['nama']) || empty($item['nama'])) {
                $this->addError('general', "Nama barang ke-{$index} tidak boleh kosong");
                return;
            }

            if (!isset($item['jumlah']) || $item['jumlah'] <= 0) {
                $this->addError('general', "Jumlah barang '{$item['nama']}' harus lebih dari 0");
                return;
            }

            if (!isset($item['harga_jual']) || $item['harga_jual'] < 0) {
                $this->addError('general', "Harga jual barang '{$item['nama']}' tidak valid");
                return;
            }

            // Validate manual items
            if (isset($item['is_manual']) && $item['is_manual']) {
                if (!isset($item['satuan']) || empty($item['satuan'])) {
                    $item['satuan'] = 'pcs'; // Set default
                }
                if (!isset($item['harga_beli_manual']) || $item['harga_beli_manual'] < 0) {
                    $this->addError('general', "Harga beli barang manual '{$item['nama']}' tidak valid");
                    return;
                }
            } else {
                // Validate regular items
                if (!isset($item['barang_id']) || empty($item['barang_id'])) {
                    $this->addError('general', "ID barang untuk '{$item['nama']}' tidak valid");
                    return;
                }

                // Check stock availability
                $availableStock = Pembelian::where('barang_id', $item['barang_id'])
                    ->where('jumlah_tersisa', '>', 0)
                    ->sum('jumlah_tersisa');

                if ($item['jumlah'] > $availableStock) {
                    $this->addError('general', "Stok {$item['nama']} tidak mencukupi. Tersedia: {$availableStock}");
                    return;
                }
            }
        }

        DB::beginTransaction();
        try {
            $totalHarga = $this->getTotalHarga();
            
            // Create main transaction record
            $penjualan = Transaksi::create([
                'kasir' => $this->kasir,
                'keterangan' => $this->keterangan,
                'tanggal' => now()->toDateString(),
                'total_harga' => $totalHarga,
            ]);

            Log::info('Penjualan created', ['id' => $penjualan->id, 'total' => $totalHarga]);

            // Process each item
            foreach ($this->items as $index => $item) {
                try {
                    if (isset($item['is_manual']) && $item['is_manual']) {
                        // Process manual item
                        $this->prosesItemManual($penjualan->id, $item);
                    } else {
                        // Process regular item
                        $this->prosesItemPenjualan($penjualan->id, $item);
                    }
                } catch (\Exception $e) {
                    $itemName = $item['nama'] ?? "Item ke-{$index}";
                    Log::error('Error processing item', [
                        'item_name' => $itemName,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception("Gagal memproses item: {$itemName} - {$e->getMessage()}");
                }
            }

            DB::commit();

            $this->showSuccessMessage($totalHarga);
            $this->resetAfterSale();
            
            Log::info('Penjualan saved successfully', [
                'total' => $totalHarga,
                'items_count' => count($this->items)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Error in simpanPenjualan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function prosesItemManual($transaksiId, $item)
    {
        try {
            Log::info('Processing manual item', [
                'transaksi_id' => $transaksiId,
                'item' => $item
            ]);

            // Prepare data untuk insert
            $data = [
                'penjualan_id' => $transaksiId, // Pastikan ini sesuai dengan kolom di tabel
                'barang_id' => null, // Explicitly set to null
                'pembelian_id' => null, // Explicitly set to null
                'nama_barang_manual' => $item['nama'],
                'jumlah' => $item['jumlah'],
                'satuan' => $item['satuan'] ?? 'pcs',
                'harga_jual' => $item['harga_jual'],
                'harga_beli_manual' => $item['harga_beli_manual'],
                'keterangan' => $item['keterangan'] ?? null,
                'is_manual' => true,
            ];

            Log::info('Creating PenjualanItem with data', $data);

            // Create manual item record
            $penjualanItem = PenjualanItem::create($data);

            Log::info('Manual item created successfully', [
                'id' => $penjualanItem->id,
                'nama' => $item['nama']
            ]);

            return $penjualanItem;

        } catch (\Exception $e) {
            Log::error('Error in prosesItemManual', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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

        // FIFO: Take from oldest stock first
        $pembelians = Pembelian::where('barang_id', $barang_id)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($pembelians as $pembelian) {
            if ($jumlahDibutuhkan <= 0) break;

            $jumlahDiambil = min($jumlahDibutuhkan, $pembelian->jumlah_tersisa);

            // Create sale item record
            PenjualanItem::create([
                'penjualan_id' => $transaksiId,
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang_id,
                'nama_barang_manual' => null,
                'jumlah' => $jumlahDiambil,
                'harga_jual' => $harga,
                'is_manual' => false,
            ]);

            // Reduce available stock
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