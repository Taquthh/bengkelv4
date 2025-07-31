<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Barang;
use App\Models\PelangganMobil;
use App\Models\TransaksiService;
use App\Models\ServiceJasaItem;
use App\Models\ServiceBarangItem;
use App\Models\Pembelian;
use App\Models\ServicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class TransaksiServices extends Component
{
    // Data Pelanggan & Mobil
    public $nama_pelanggan = '';
    public $kontak = '';
    public $jenis_pelanggan = 'perorangan';
    public $nama_perusahaan = '';
    
    // Data Mobil
    public $merk_mobil = '';
    public $tipe_mobil = '';
    public $nopol = '';
    public $tahun = '';
    public $warna = '';
    public $catatan_mobil = '';
    
    // Data Service
    public $keluhan = '';
    public $diagnosa = '';
    public $pekerjaan_dilakukan = '';
    public $kasir;
    
    // Data Barang
    public $barangs = [];
    public $selectedBarangInfo = null;
    public $barang_id = '';
    public $itemsBarang = [];
    public $jumlah = 1;
    public $harga_jual = 0;

    public $nama_barang_manual = '';
    public $jumlah_manual = 1;
    public $satuan_manual = 'pcs';
    public $harga_jual_manual = 0;
    
    // Data Jasa
    public $itemsJasa = [];
    public $nama_jasa = '';
    public $harga_jasa = 0;
    public $keterangan_jasa = '';
    
    // Enhanced Payment System
    public $metode_pembayaran = 'tunai';
    public $strategi_pembayaran = 'cicilan'; // Set default ke cicilan
    public $status_pekerjaan = 'belum_dikerjakan'; // Keep this for work status
    public $jumlah_dibayar_sekarang = 0;
    public $jatuh_tempo = '';
    public $no_surat_pesanan = '';
    public $keterangan_pembayaran = '';
    
    // Payment tracking
    public $riwayat_pembayaran = [];
    public $total_sudah_dibayar = 0;
    
    // Step management
    public $currentStep = 1;
    
    // Loading state
    public $isLoading = false;
    public $isSaving = false;
    
    protected $rules = [
        'nama_pelanggan' => 'required|string|max:255',
        'kontak' => 'nullable|string|max:20',
        'merk_mobil' => 'required|string|max:100',
        'tipe_mobil' => 'required|string|max:100',
        'nopol' => 'required|string|max:15',
        'keluhan' => 'required|string',
        'metode_pembayaran' => 'required|in:tunai,transfer',
        'jumlah_dibayar_sekarang' => 'required|numeric|min:0',
        'status_pekerjaan' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',

        // Rules untuk barang manual
        'nama_barang_manual' => 'required|string|max:255',
        'jumlah_manual' => 'required|integer|min:1',
        'satuan_manual' => 'required|string|max:20',
        'harga_jual_manual' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
        'merk_mobil.required' => 'Merk mobil wajib diisi.',
        'tipe_mobil.required' => 'Tipe mobil wajib diisi.',
        'nopol.required' => 'Nomor polisi wajib diisi.',
        'keluhan.required' => 'Keluhan wajib diisi.',
        'jumlah_dibayar_sekarang.required' => 'Jumlah pembayaran wajib diisi.',
        'jumlah_dibayar_sekarang.min' => 'Jumlah pembayaran tidak boleh negatif.',

        // Messages untuk barang manual
        'nama_barang_manual.required' => 'Nama barang wajib diisi.',
        'nama_barang_manual.max' => 'Nama barang maksimal 255 karakter.',
        'jumlah_manual.required' => 'Jumlah barang wajib diisi.',
        'jumlah_manual.min' => 'Jumlah minimal 1.',
        'satuan_manual.required' => 'Satuan wajib dipilih.',
        'harga_jual_manual.required' => 'Harga jual wajib diisi.',
        'harga_jual_manual.min' => 'Harga jual tidak boleh negatif.',
    ];

    public function mount()
    {
        $this->kasir = Auth::user()->name ?? 'Admin';
        $this->loadBarangs();
        $this->jatuh_tempo = now()->addDays(30)->format('Y-m-d');
        $this->jumlah_dibayar_sekarang = 0;
        $this->status_pekerjaan = 'belum_dikerjakan';
        $this->strategi_pembayaran = 'cicilan'; // Set ke cicilan by default
    }

    public function loadBarangs()
    {
        try {
            $this->barangs = Barang::with(['pembelians' => function($query) {
                $query->where('jumlah_tersisa', '>', 0)->orderBy('tanggal', 'asc');
            }])->get()->map(function($barang) {
                $totalStok = $barang->pembelians->sum('jumlah_tersisa');
                $supplierCount = $barang->pembelians->groupBy('supplier')->count();
                $avgHPP = $barang->pembelians->avg('harga_beli');
                
                return [
                    'id' => $barang->id,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'tipe' => $barang->tipe,
                    'satuan' => $barang->satuan,
                    'deskripsi' => $barang->deskripsi,
                    'total_stok' => $totalStok,
                    'supplier_count' => $supplierCount,
                    'avg_hpp' => $avgHPP ?? 0,
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
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading barangs: ' . $e->getMessage());
            $this->barangs = [];
            session()->flash('error', 'Gagal memuat data barang: ' . $e->getMessage());
        }
    }

    public function updatedNopol($value)
    {
        if ($value) {
            try {
                $existingCustomer = PelangganMobil::where('nopol', strtoupper($value))->first();
                if ($existingCustomer) {
                    $this->nama_pelanggan = $existingCustomer->nama_pelanggan;
                    $this->kontak = $existingCustomer->kontak;
                    $this->jenis_pelanggan = $existingCustomer->jenis_pelanggan;
                    $this->nama_perusahaan = $existingCustomer->nama_perusahaan;
                    $this->merk_mobil = $existingCustomer->merk_mobil;
                    $this->tipe_mobil = $existingCustomer->tipe_mobil;
                    $this->tahun = $existingCustomer->tahun;
                    $this->warna = $existingCustomer->warna;
                    $this->catatan_mobil = $existingCustomer->catatan_mobil;
                }
            } catch (\Exception $e) {
                Log::error('Error in updatedNopol: ' . $e->getMessage());
            }
        }
    }

    public function updatedJenisPelanggan()
    {
        logger("Jenis pelanggan berubah ke: " . $this->jenis_pelanggan);
    }

    

    // Method untuk reset form barang manual
    public function resetManualInputs()
    {
        $this->nama_barang_manual = '';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->resetErrorBag(['nama_barang_manual', 'jumlah_manual', 'satuan_manual', 'harga_jual_manual', 'catatan_manual']);
    }

    public function selectBarang($barangId)
    {
        $barang = collect($this->barangs)->firstWhere('id', $barangId);
        
        if ($barang && $barang['total_stok'] > 0) {
            $this->selectedBarangInfo = $barang;
            $this->barang_id = $barangId;
            $this->jumlah = 1;
            $this->harga_jual = round($barang['avg_hpp'] * 1.3, 0);
        } else {
            $this->addError('general', 'Barang tidak tersedia atau stok habis.');
        }
    }

    public function tambahItemBarang()
    {
        $this->validate([
            'jumlah' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        if (!$this->selectedBarangInfo) {
            $this->addError('general', 'Pilih barang terlebih dahulu.');
            return;
        }

        try {
            $totalStokTersedia = Pembelian::where('barang_id', $this->barang_id)
                ->where('jumlah_tersisa', '>', 0)
                ->sum('jumlah_tersisa');

            if ($this->jumlah > $totalStokTersedia) {
                $this->addError('jumlah', 'Stok tidak mencukupi. Stok tersedia: ' . $totalStokTersedia);
                return;
            }

            $existingIndex = collect($this->itemsBarang)->search(function($item) {
                return isset($item['barang_id']) && $item['barang_id'] == $this->barang_id;
            });

            if ($existingIndex !== false) {
                $totalJumlah = $this->itemsBarang[$existingIndex]['jumlah'] + $this->jumlah;
                if ($totalJumlah > $totalStokTersedia) {
                    $this->addError('jumlah', 'Total jumlah melebihi stok tersedia.');
                    return;
                }
                $this->itemsBarang[$existingIndex]['jumlah'] = $totalJumlah;
                $this->itemsBarang[$existingIndex]['harga_jual'] = $this->harga_jual;
                $this->itemsBarang[$existingIndex]['subtotal'] = $totalJumlah * $this->harga_jual;
            } else {
                // Pastikan semua field yang diperlukan ada
                $newRegularItem = [
                    'barang_id' => $this->barang_id,
                    'nama' => $this->selectedBarangInfo['nama'] ?? 'Nama tidak ditemukan',
                    'jumlah' => $this->jumlah,
                    'harga_jual' => $this->harga_jual,
                    'subtotal' => $this->jumlah * $this->harga_jual,
                    'stok_tersedia' => $totalStokTersedia,
                    'is_manual' => false // Explicitly set as regular item
                ];

                $this->itemsBarang[] = $newRegularItem;
            }

            $this->resetFormInputs();
            $this->dispatch('item-added');
            $this->updatePaymentAmount();

            Log::info('Regular item added successfully', [
                'nama' => $this->selectedBarangInfo['nama'] ?? 'Nama tidak ditemukan',
                'jumlah' => $this->jumlah,
                'total_items' => count($this->itemsBarang)
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding item barang: ' . $e->getMessage());
            $this->addError('general', 'Terjadi kesalahan saat menambah item.');
        }
    }

    public function updatedBarangId($value)
    {
        $this->selectedBarangInfo = Barang::find($value)?->toArray();
    }


    public function hapusItemBarang($index)
    {
        if (isset($this->itemsBarang[$index])) {
            unset($this->itemsBarang[$index]);
            $this->itemsBarang = array_values($this->itemsBarang);
            $this->updatePaymentAmount();
        }
    }

    public function tambahJasa()
    {
        $this->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric|min:0',
        ]);

        $this->itemsJasa[] = [
            'nama_jasa' => $this->nama_jasa,
            'harga_jasa' => $this->harga_jasa,
            'subtotal' => $this->harga_jasa,
            'keterangan' => $this->keterangan_jasa
        ];

        $this->resetJasaInputs();
        $this->updatePaymentAmount();
    }

    public function hapusJasa($index)
    {
        if (isset($this->itemsJasa[$index])) {
            unset($this->itemsJasa[$index]);
            $this->itemsJasa = array_values($this->itemsJasa);
            $this->updatePaymentAmount();
        }
    }

    // Fixed payment amount calculation
    public function updatePaymentAmount()
    {
        // Sistem cicilan fleksibel - tidak ada pembatasan
        $total = $this->getTotalKeseluruhanProperty();
        
        // Pastikan tidak melebihi total dan tidak negatif
        if ($this->jumlah_dibayar_sekarang > $total) {
            $this->jumlah_dibayar_sekarang = $total;
        }
        
        if ($this->jumlah_dibayar_sekarang < 0) {
            $this->jumlah_dibayar_sekarang = 0;
        }
    }

    // Method ini bisa dihapus atau disederhanakan:
    public function updatedStrategiPembayaran($value)
    {
        // Tidak perlu lagi karena selalu cicilan
        $this->updatePaymentAmount();
    }

    public function updatedStatusPekerjaan($value)
    {
        $this->updatePaymentAmount();
    }

    // Fixed payment amount validation
    public function updatedJumlahDibayarSekarang($value)
    {
        $total = $this->getTotalKeseluruhanProperty();
        
        // Ensure payment doesn't exceed total
        if ($value > $total) {
            $this->jumlah_dibayar_sekarang = $total;
        }
        
        // Ensure payment is not negative
        if ($value < 0) {
            $this->jumlah_dibayar_sekarang = 0;
        }
    }

    public function resetFormInputs()
    {
        $this->selectedBarangInfo = null;
        $this->barang_id = '';
        $this->jumlah = 1;
        $this->harga_jual = 0;
        $this->resetErrorBag(['jumlah', 'harga_jual']);
        $this->dispatch('form-reset');
    }

    public function resetJasaInputs()
    {
        $this->nama_jasa = '';
        $this->harga_jasa = 0;
        $this->keterangan_jasa = '';
        $this->resetErrorBag(['nama_jasa', 'harga_jasa']);
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'nama_pelanggan' => 'required',
                'merk_mobil' => 'required',
                'tipe_mobil' => 'required',
                'nopol' => 'required',
                'keluhan' => 'required',
            ]);
        }
        
        if ($this->currentStep < 4) {
            $this->currentStep++;
            
            // Update payment when reaching payment step
            if ($this->currentStep == 4) {
                $this->updatePaymentAmount();
            }
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function getTotalBarangProperty()
    {
        return collect($this->itemsBarang)->sum('subtotal');
    }

    public function getTotalJasaProperty()
    {
        return collect($this->itemsJasa)->sum('subtotal');
    }

    public function getTotalKeseluruhanProperty()
    {
        return $this->getTotalBarangProperty() + $this->getTotalJasaProperty();
    }

    // Fixed payment status calculation
    public function getStatusPembayaranProperty()
    {
        $total = $this->getTotalKeseluruhanProperty();
        $totalDibayar = $this->total_sudah_dibayar + $this->jumlah_dibayar_sekarang;
        
        if ($total <= 0) {
            return 'lunas'; // No amount to pay
        }
        
        if ($totalDibayar >= $total) {
            return 'lunas';
        } elseif ($totalDibayar > 0) {
            return 'sebagian';
        } else {
            return 'belum';
        }
    }

    // Fixed remaining payment calculation
    public function getSisaPembayaranProperty()
    {
        $total = $this->getTotalKeseluruhanProperty();
        $totalDibayar = $this->total_sudah_dibayar + $this->jumlah_dibayar_sekarang;
        return max(0, $total - $totalDibayar);
    }

    // Fixed payment validation logic
    public function canMakePayment()
    {
        return true; // Selalu bisa bayar karena sistem cicilan fleksibel
    }

public function simpanTransaksi()
    {
        if ($this->isSaving) {
            return;
        }

        $this->isSaving = true;
        $this->isLoading = true;

        try {
            $this->resetErrorBag();
            $this->dispatch('transaction-saving');

            Log::info('Starting simpanTransaksi', [
                'items_barang' => $this->itemsBarang,
                'items_jasa' => $this->itemsJasa,
            ]);

            // Basic validation
            $this->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'merk_mobil' => 'required|string|max:100',
                'tipe_mobil' => 'required|string|max:100',
                'nopol' => 'required|string|max:15',
                'keluhan' => 'required|string',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                'status_pekerjaan' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
                'jumlah_dibayar_sekarang' => 'required|numeric|min:0',
            ]);

            if (empty($this->itemsBarang) && empty($this->itemsJasa)) {
                $this->addError('general', 'Minimal tambahkan satu item barang atau jasa');
                return;
            }

            // Validate items data integrity
            foreach ($this->itemsBarang as $index => $item) {
                if (!is_array($item)) {
                    $this->addError('general', "Item barang ke-{$index} tidak valid");
                    return;
                }

                // Check required fields for all items
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

                if (!isset($item['subtotal']) || $item['subtotal'] < 0) {
                    $this->addError('general', "Subtotal barang '{$item['nama']}' tidak valid");
                    return;
                }

                // Validate manual items
                if (isset($item['is_manual']) && $item['is_manual']) {
                    if (!isset($item['satuan']) || empty($item['satuan'])) {
                        $item['satuan'] = 'pcs'; // Set default
                    }
                    Log::info("Manual item validated", ['item' => $item]);
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
                    Log::info("Regular item validated", ['item' => $item, 'available_stock' => $availableStock]);
                }
            }

            // Validate jasa items
            foreach ($this->itemsJasa as $index => $jasa) {
                if (!is_array($jasa)) {
                    $this->addError('general', "Item jasa ke-{$index} tidak valid");
                    return;
                }

                if (!isset($jasa['nama_jasa']) || empty($jasa['nama_jasa'])) {
                    $this->addError('general', "Nama jasa ke-{$index} tidak boleh kosong");
                    return;
                }

                if (!isset($jasa['harga_jasa']) || $jasa['harga_jasa'] < 0) {
                    $this->addError('general', "Harga jasa '{$jasa['nama_jasa']}' tidak valid");
                    return;
                }
            }

            DB::beginTransaction();

            // 1. Create PelangganMobil
            $pelangganMobil = PelangganMobil::updateOrCreate(
                ['nopol' => strtoupper($this->nopol)],
                [
                    'nama_pelanggan' => $this->nama_pelanggan,
                    'kontak' => $this->kontak ?: null,
                    'jenis_pelanggan' => $this->jenis_pelanggan,
                    'nama_perusahaan' => $this->nama_perusahaan ?: null,
                    'merk_mobil' => $this->merk_mobil,
                    'tipe_mobil' => $this->tipe_mobil,
                    'tahun' => $this->tahun ?: null,
                    'warna' => $this->warna ?: null,
                    'catatan_mobil' => $this->catatan_mobil ?: null,
                ]
            );

            Log::info('PelangganMobil created/updated', ['id' => $pelangganMobil->id]);

            // 2. Create TransaksiService
            $transaksiService = TransaksiService::create([
                'invoice' => $this->generateInvoice(),
                'pelanggan_mobil_id' => $pelangganMobil->id,
                'kasir' => $this->kasir,
                'tanggal_service' => now()->toDateString(),
                'keluhan' => $this->keluhan,
                'diagnosa' => $this->diagnosa ?: null,
                'pekerjaan_dilakukan' => $this->pekerjaan_dilakukan ?: null,
                'metode_pembayaran' => $this->metode_pembayaran,
                'strategi_pembayaran' => 'cicilan', // Set tetap ke cicilan
                'status_pekerjaan' => $this->status_pekerjaan, // Set default
                'status_pembayaran' => $this->getStatusPembayaranProperty(),
                'total_barang' => $this->getTotalBarangProperty(),
                'total_jasa' => $this->getTotalJasaProperty(),
                'total_keseluruhan' => $this->getTotalKeseluruhanProperty(),
                'total_sudah_dibayar' => $this->total_sudah_dibayar + $this->jumlah_dibayar_sekarang,
                'sisa_pembayaran' => $this->getSisaPembayaranProperty(),
                'jatuh_tempo' => ($this->getStatusPembayaranProperty() !== 'lunas') ? $this->jatuh_tempo : null,
                'no_surat_pesanan' => $this->no_surat_pesanan ?: null,
                'keterangan_pembayaran' => $this->keterangan_pembayaran ?: null,
            ]);

            Log::info('TransaksiService created', ['id' => $transaksiService->id, 'invoice' => $transaksiService->invoice]);

            // 3. Create ServiceBarangItems
            foreach ($this->itemsBarang as $index => $item) {
                try {
                    // Ensure item is valid
                    if (!is_array($item) || !isset($item['nama'])) {
                        throw new \Exception("Item barang ke-{$index} tidak valid atau nama kosong");
                    }

                    $itemName = $item['nama'];
                    Log::info("Processing barang item {$index}: {$itemName}", ['item' => $item]);

                    if (isset($item['is_manual']) && $item['is_manual']) {
                        // Manual item
                        $manualData = [
                            'transaksi_service_id' => $transaksiService->id,
                            'barang_id' => null,
                            'pembelian_id' => null,
                            'nama_barang_manual' => $itemName,
                            'jumlah' => (int) $item['jumlah'],
                            'satuan' => $item['satuan'] ?? 'pcs',
                            'harga_jual' => (float) $item['harga_jual'],
                            'subtotal' => (float) $item['subtotal'],
                            'is_manual' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        Log::info('Creating manual item', ['data' => $manualData]);

                        $insertedId = DB::table('service_barang_items')->insertGetId($manualData);
                        
                        Log::info('Manual item created successfully', ['id' => $insertedId, 'nama' => $itemName]);

                    } else {
                        // Regular item - with stock reduction
                        if (!isset($item['barang_id']) || empty($item['barang_id'])) {
                            throw new \Exception("Barang ID tidak valid untuk item: {$itemName}");
                        }

                        $usedPembelians = $this->reduceStockFifo($item['barang_id'], $item['jumlah']);
                        
                        foreach ($usedPembelians as $used) {
                            $regularData = [
                                'transaksi_service_id' => $transaksiService->id,
                                'barang_id' => $item['barang_id'],
                                'pembelian_id' => $used['pembelian_id'],
                                'nama_barang_manual' => null,
                                'jumlah' => (int) $used['jumlah'],
                                'satuan' => null,
                                'harga_jual' => (float) $item['harga_jual'],
                                'subtotal' => (float) ($used['jumlah'] * $item['harga_jual']),
                                'is_manual' => false,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            Log::info('Creating regular item', ['data' => $regularData]);

                            $insertedId = DB::table('service_barang_items')->insertGetId($regularData);
                            
                            Log::info('Regular item created successfully', ['id' => $insertedId, 'nama' => $itemName]);
                        }
                    }

                } catch (\Exception $e) {
                    $itemName = isset($item['nama']) ? $item['nama'] : "Item ke-{$index}";
                    Log::error('Error creating ServiceBarangItem', [
                        'item_index' => $index,
                        'item_name' => $itemName,
                        'item' => $item,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception("Gagal menyimpan item barang: {$itemName} - {$e->getMessage()}");
                }
            }

            Log::info('All ServiceBarangItems created successfully', ['count' => count($this->itemsBarang)]);

            // 4. Create ServiceJasaItems
            foreach ($this->itemsJasa as $index => $jasa) {
                try {
                    if (!is_array($jasa) || !isset($jasa['nama_jasa'])) {
                        throw new \Exception("Item jasa ke-{$index} tidak valid");
                    }

                    ServiceJasaItem::create([
                        'transaksi_service_id' => $transaksiService->id,
                        'nama_jasa' => $jasa['nama_jasa'],
                        'harga_jasa' => $jasa['harga_jasa'],
                        'subtotal' => $jasa['subtotal'],
                        'keterangan' => $jasa['keterangan'] ?? null,
                    ]);

                    Log::info('ServiceJasaItem created', ['nama_jasa' => $jasa['nama_jasa']]);

                } catch (\Exception $e) {
                    $jasaName = isset($jasa['nama_jasa']) ? $jasa['nama_jasa'] : "Jasa ke-{$index}";
                    Log::error('Error creating ServiceJasaItem', [
                        'jasa_index' => $index,
                        'jasa_name' => $jasaName,
                        'jasa' => $jasa,
                        'error' => $e->getMessage()
                    ]);
                    throw new \Exception("Gagal menyimpan item jasa: {$jasaName} - {$e->getMessage()}");
                }
            }

            // 5. Create payment if needed
            if ($this->jumlah_dibayar_sekarang > 0) {
                ServicePayment::create([
                    'transaksi_service_id' => $transaksiService->id,
                    'tanggal_bayar' => now()->toDateString(),
                    'jumlah_bayar' => $this->jumlah_dibayar_sekarang,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'keterangan' => $this->keterangan_pembayaran ?: 'Pembayaran awal',
                    'kasir' => $this->kasir,
                ]);

                Log::info('ServicePayment created', ['jumlah' => $this->jumlah_dibayar_sekarang]);
            }

            DB::commit();

            $statusText = $this->getStatusText(
                $this->getStatusPembayaranProperty(), 
                $this->strategi_pembayaran, 
                $this->status_pekerjaan, 
                $this->getSisaPembayaranProperty()
            );

            $successMessage = 'Transaksi service berhasil disimpan!' . 
                ' | Invoice: ' . $transaksiService->invoice . 
                ' | Total: Rp' . number_format($transaksiService->total_keseluruhan, 0, ',', '.') .
                $statusText;

            session()->flash('message', $successMessage);
            
            Log::info('Transaction saved successfully', [
                'invoice' => $transaksiService->invoice,
                'total' => $transaksiService->total_keseluruhan,
            ]);

            $this->isSaving = false;
            $this->isLoading = false;

            return redirect()->route('service.invoice', ['id' => $transaksiService->id]);

        } catch (\Exception $e) {
            DB::rollback();
            $this->isSaving = false;
            $this->isLoading = false;
            
            Log::error('Error in simpanTransaksi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'items_barang' => $this->itemsBarang,
                'items_jasa' => $this->itemsJasa,
            ]);
            
            $this->addError('general', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }

    // Perbaiki method tambahBarangManual untuk memastikan data lengkap
    public function tambahBarangManual()
    {
        $this->validate([
            'nama_barang_manual' => 'required|string|max:255',
            'jumlah_manual' => 'required|integer|min:1',
            'satuan_manual' => 'required|string|max:20',
            'harga_jual_manual' => 'required|numeric|min:0',
        ]);

        try {
            // Generate unique temporary ID untuk barang manual
            $tempId = 'manual_' . uniqid() . '_' . time();
            
            // Cek apakah barang dengan nama yang sama sudah ada di items
            $existingIndex = collect($this->itemsBarang)->search(function($item) {
                return isset($item['is_manual']) && $item['is_manual'] && 
                       strtolower($item['nama']) === strtolower($this->nama_barang_manual);
            });

            if ($existingIndex !== false) {
                // Update existing manual item
                $this->itemsBarang[$existingIndex]['jumlah'] += $this->jumlah_manual;
                $this->itemsBarang[$existingIndex]['harga_jual'] = $this->harga_jual_manual;
                $this->itemsBarang[$existingIndex]['subtotal'] = $this->itemsBarang[$existingIndex]['jumlah'] * $this->harga_jual_manual;
                
                session()->flash('success', 'Barang manual berhasil diperbarui!');
            } else {
                // Add new manual item
                $this->itemsBarang[] = [
                    'barang_id' => $tempId, // Temporary ID
                    'nama' => $this->nama_barang_manual,
                    'jumlah' => $this->jumlah_manual,
                    'satuan' => $this->satuan_manual,
                    'harga_jual' => $this->harga_jual_manual,
                    'subtotal' => $this->jumlah_manual * $this->harga_jual_manual,
                    'is_manual' => true, // Flag untuk identifikasi barang manual
                    'stok_tersedia' => 'ORDER' // Menunjukkan bahwa ini adalah order
                ];
                
                session()->flash('success', 'Barang manual berhasil ditambahkan untuk diorder!');
            }

            // Reset form inputs
            $this->resetManualInputs();
            
            // Update payment amount
            $this->updatePaymentAmount();
            
            // Emit event untuk UI
            $this->dispatch('manual-item-added');
            
            // Hide manual input form
            $this->dispatch('hide-manual-form');
            
        } catch (\Exception $e) {
            Log::error('Error adding manual item: ' . $e->getMessage());
            $this->addError('general', 'Terjadi kesalahan saat menambah barang manual.');
        }
    }

    // Perbaiki method tambahItemBarang untuk memastikan data lengkap
    

    private function getStatusText($statusPembayaran, $strategiPembayaran, $statusPekerjaan, $sisaPembayaran)
    {
        $statusText = '';
        
        // Work status
        switch ($statusPekerjaan) {
            case 'belum_dikerjakan':
                $statusText .= ' | Pekerjaan: BELUM DIKERJAKAN';
                break;
            case 'sedang_dikerjakan':
                $statusText .= ' | Pekerjaan: SEDANG DIKERJAKAN';
                break;
            case 'selesai':
                $statusText .= ' | Pekerjaan: SELESAI';
                break;
        }
        
        // Payment status
        switch ($statusPembayaran) {
            case 'lunas':
                $statusText .= ' | Pembayaran: LUNAS';
                break;
            case 'sebagian':
                $statusText .= ' | Pembayaran: SEBAGIAN (Sisa: Rp' . number_format($sisaPembayaran, 0, ',', '.') . ')';
                break;
            case 'belum':
                switch ($strategiPembayaran) {
                    case 'bayar_akhir':
                        $statusText .= ' | Pembayaran: MENUNGGU SELESAI';
                        break;
                    case 'bayar_dimuka':
                        $statusText .= ' | Pembayaran: MENUNGGU MULAI';
                        break;
                    case 'cicilan':
                        $statusText .= ' | Pembayaran: CICILAN (Jatuh tempo: ' . Carbon::parse($this->jatuh_tempo)->format('d/m/Y') . ')';
                        break;
                }
                break;
        }
        
        return $statusText;
    }

private function generateInvoice(): string
{
    $prefix = 'FJS';
    $month = strtoupper(now()->format('M'));
    $year = now()->format('Y');
    $dateSegment = "{$prefix}/{$month}/{$year}";

    $maxRetries = 5;
    
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        try {
            return DB::transaction(function () use ($dateSegment, $prefix, $month, $year) {
                // Gunakan LIKE dengan pattern yang lebih spesifik
                $pattern = "___/{$prefix}/{$month}/{$year}";
                
                $result = DB::selectOne("
                    SELECT COALESCE(MAX(CAST(SUBSTRING(invoice, 1, 3) AS UNSIGNED)), 0) as last_number
                    FROM transaksi_services 
                    WHERE invoice LIKE ? 
                    AND LENGTH(invoice) = ?
                    FOR UPDATE
                ", [$pattern, strlen($pattern)]);

                $newNumber = ($result->last_number ?? 0) + 1;
                $numberFormatted = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
                
                return "{$numberFormatted}/{$dateSegment}";
            });
        } catch (\Exception $e) {
            if ($attempt >= $maxRetries) {
                throw new \Exception("Gagal generate invoice setelah {$maxRetries} percobaan: " . $e->getMessage());
            }
            
            // Random delay untuk mengurangi collision
            usleep(rand(10000, 50000)); // 10-50ms
        }
    }
    
    // Fallback
    throw new \Exception("Unexpected error in invoice generation");
}



    private function reduceStockFifo($barangId, $jumlahDibutuhkan)
    {
        try {
            $pembelians = Pembelian::where('barang_id', $barangId)
                ->where('jumlah_tersisa', '>', 0)
                ->orderBy('tanggal', 'asc')
                ->orderBy('id', 'asc')
                ->lockForUpdate() // Add lock to prevent race conditions
                ->get();

            $usedPembelians = [];
            $sisaKebutuhan = $jumlahDibutuhkan;

            foreach ($pembelians as $pembelian) {
                if ($sisaKebutuhan <= 0) break;

                $jumlahAmbil = min($sisaKebutuhan, $pembelian->jumlah_tersisa);
                
                if ($jumlahAmbil > 0) {
                    $usedPembelians[] = [
                        'pembelian_id' => $pembelian->id,
                        'jumlah' => $jumlahAmbil,
                        'harga_beli' => $pembelian->harga_beli
                    ];

                    $pembelian->decrement('jumlah_tersisa', $jumlahAmbil);
                    $sisaKebutuhan -= $jumlahAmbil;
                }
            }

            if ($sisaKebutuhan > 0) {
                throw new \Exception("Stok tidak mencukupi untuk barang ID: {$barangId}. Kurang: {$sisaKebutuhan}");
            }

            return $usedPembelians;
        } catch (\Exception $e) {
            Log::error('Error in reduceStockFifo: ' . $e->getMessage(), [
                'barang_id' => $barangId,
                'jumlah_dibutuhkan' => $jumlahDibutuhkan
            ]);
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.transaksi-services');
    }
}