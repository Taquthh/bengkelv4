<?php

namespace App\Livewire;

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
    
    // Data Jasa
    public $itemsJasa = [];
    public $nama_jasa = '';
    public $harga_jasa = 0;
    public $keterangan_jasa = '';
    
    // Enhanced Payment System
    public $metode_pembayaran = 'tunai';
    public $status_pekerjaan = 'belum_dikerjakan';
    public $strategi_pembayaran = 'bayar_akhir';
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
        'strategi_pembayaran' => 'required|in:bayar_akhir,bayar_dimuka,cicilan',
        'jumlah_dibayar_sekarang' => 'required|numeric|min:0',
        'status_pekerjaan' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
    ];

    protected $messages = [
        'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
        'merk_mobil.required' => 'Merk mobil wajib diisi.',
        'tipe_mobil.required' => 'Tipe mobil wajib diisi.',
        'nopol.required' => 'Nomor polisi wajib diisi.',
        'keluhan.required' => 'Keluhan wajib diisi.',
        'jumlah_dibayar_sekarang.required' => 'Jumlah pembayaran wajib diisi.',
        'jumlah_dibayar_sekarang.min' => 'Jumlah pembayaran tidak boleh negatif.',
    ];

    public function mount()
    {
        $this->kasir = Auth::user()->name ?? 'Admin';
        $this->loadBarangs();
        $this->jatuh_tempo = now()->addDays(30)->format('Y-m-d');
        $this->jumlah_dibayar_sekarang = 0;
        $this->status_pekerjaan = 'belum_dikerjakan';
        $this->strategi_pembayaran = 'bayar_akhir';
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
                return $item['barang_id'] == $this->barang_id;
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
                $this->itemsBarang[] = [
                    'barang_id' => $this->barang_id,
                    'nama' => $this->selectedBarangInfo['nama'],
                    'jumlah' => $this->jumlah,
                    'harga_jual' => $this->harga_jual,
                    'subtotal' => $this->jumlah * $this->harga_jual,
                    'stok_tersedia' => $totalStokTersedia
                ];
            }

            $this->resetFormInputs();
            $this->dispatch('item-added');
            
            // Update payment amount after adding item
            $this->updatePaymentAmount();
        } catch (\Exception $e) {
            Log::error('Error adding item barang: ' . $e->getMessage());
            $this->addError('general', 'Terjadi kesalahan saat menambah item.');
        }
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
        $total = $this->getTotalKeseluruhanProperty();
        
        // Only auto-set payment amount, don't force it
        switch ($this->strategi_pembayaran) {
            case 'bayar_dimuka':
                // Can pay when work starts (sedang_dikerjakan or selesai)
                if (in_array($this->status_pekerjaan, ['sedang_dikerjakan', 'selesai'])) {
                    // Don't auto-set to full amount, let user decide
                    if ($this->jumlah_dibayar_sekarang == 0) {
                        $this->jumlah_dibayar_sekarang = $total;
                    }
                } else {
                    $this->jumlah_dibayar_sekarang = 0;
                }
                break;
                
            case 'bayar_akhir':
                // Can only pay when work is completed
                if ($this->status_pekerjaan === 'selesai') {
                    if ($this->jumlah_dibayar_sekarang == 0) {
                        $this->jumlah_dibayar_sekarang = $total;
                    }
                } else {
                    $this->jumlah_dibayar_sekarang = 0;
                }
                break;
                
            case 'cicilan':
                // Flexible payment - don't auto-change amount
                // Keep whatever amount user has entered
                break;
        }
    }

    // Fixed payment strategy handlers
    public function updatedStrategiPembayaran($value)
    {
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
            return 'belum_bayar';
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
        switch ($this->strategi_pembayaran) {
            case 'bayar_akhir':
                return $this->status_pekerjaan === 'selesai';
                
            case 'bayar_dimuka':
                return in_array($this->status_pekerjaan, ['sedang_dikerjakan', 'selesai']);
                
            case 'cicilan':
                return true; // Always can pay
                
            default:
                return false;
        }
    }

    public function simpanTransaksi()
    {
        // Prevent double submission
        if ($this->isSaving) {
            return;
        }

        // Set saving state
        $this->isSaving = true;
        $this->isLoading = true;
        
        try {
            // Clear previous errors
            $this->resetErrorBag();
            
            // Emit saving event for UI feedback
            $this->dispatch('transaction-saving');
            
            // Debug log
            Log::info('Starting simpanTransaksi', [
                'user_id' => auth()->id(),
                'nama_pelanggan' => $this->nama_pelanggan,
                'total_keseluruhan' => $this->getTotalKeseluruhanProperty(),
                'items_barang_count' => count($this->itemsBarang),
                'items_jasa_count' => count($this->itemsJasa),
                'strategi_pembayaran' => $this->strategi_pembayaran,
                'status_pekerjaan' => $this->status_pekerjaan,
                'jumlah_dibayar_sekarang' => $this->jumlah_dibayar_sekarang
            ]);

            // Basic validation
            $validationRules = [
                'nama_pelanggan' => 'required|string|max:255',
                'merk_mobil' => 'required|string|max:100',
                'tipe_mobil' => 'required|string|max:100', 
                'nopol' => 'required|string|max:15',
                'keluhan' => 'required|string',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                'strategi_pembayaran' => 'required|in:bayar_akhir,bayar_dimuka,cicilan',
                'status_pekerjaan' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
                'jumlah_dibayar_sekarang' => 'required|numeric|min:0',
            ];

            $this->validate($validationRules);

            // Check if at least one item exists
            if (empty($this->itemsBarang) && empty($this->itemsJasa)) {
                $this->addError('general', 'Minimal tambahkan satu item barang atau jasa');
                $this->dispatch('transaction-error', ['message' => 'Minimal tambahkan satu item barang atau jasa']);
                return;
            }

            $totalKeseluruhan = $this->getTotalKeseluruhanProperty();
            
            // Validate payment amount
            if ($this->jumlah_dibayar_sekarang > $totalKeseluruhan) {
                $this->addError('jumlah_dibayar_sekarang', 'Jumlah dibayar tidak boleh melebihi total keseluruhan');
                $this->dispatch('transaction-error', ['message' => 'Jumlah dibayar tidak boleh melebihi total keseluruhan']);
                return;
            }

            // Fixed business logic validation based on payment strategy
            if ($this->jumlah_dibayar_sekarang > 0) {
                if (!$this->canMakePayment()) {
                    $errorMessage = '';
                    switch ($this->strategi_pembayaran) {
                        case 'bayar_dimuka':
                            $errorMessage = 'Untuk strategi bayar dimuka, pekerjaan harus dimulai dulu sebelum ada pembayaran';
                            break;
                        case 'bayar_akhir':
                            $errorMessage = 'Untuk strategi bayar akhir, pekerjaan harus selesai dulu sebelum ada pembayaran';
                            break;
                    }
                    $this->addError('general', $errorMessage);
                    $this->dispatch('transaction-error', ['message' => $errorMessage]);
                    return;
                }
            }

            // Validate stock availability before transaction
            foreach ($this->itemsBarang as $item) {
                $availableStock = Pembelian::where('barang_id', $item['barang_id'])
                    ->where('jumlah_tersisa', '>', 0)
                    ->sum('jumlah_tersisa');
                    
                if ($item['jumlah'] > $availableStock) {
                    $this->addError('general', "Stok {$item['nama']} tidak mencukupi. Tersedia: {$availableStock}");
                    $this->dispatch('transaction-error', ['message' => "Stok {$item['nama']} tidak mencukupi"]);
                    return;
                }
            }

            // Set default jatuh tempo for unpaid transactions
            if ($this->getStatusPembayaranProperty() !== 'lunas' && empty($this->jatuh_tempo)) {
                $this->jatuh_tempo = now()->addDays(30)->format('Y-m-d');
            }

            // Start database transaction
            DB::beginTransaction();
            
            // 1. Create or update PelangganMobil
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

            // 2. Calculate totals and payment status
            $totalBarang = $this->getTotalBarangProperty();
            $totalJasa = $this->getTotalJasaProperty();
            $statusPembayaran = $this->getStatusPembayaranProperty();
            $sisaPembayaran = $this->getSisaPembayaranProperty();

            // 3. Create TransaksiService
            $transaksiService = TransaksiService::create([
                'invoice' => $this->generateInvoice(),
                'pelanggan_mobil_id' => $pelangganMobil->id,
                'kasir' => $this->kasir,
                'tanggal_service' => now()->toDateString(),
                'keluhan' => $this->keluhan,
                'diagnosa' => $this->diagnosa ?: null,
                'pekerjaan_dilakukan' => $this->pekerjaan_dilakukan ?: null,
                'metode_pembayaran' => $this->metode_pembayaran,
                'strategi_pembayaran' => $this->strategi_pembayaran,
                'status_pekerjaan' => $this->status_pekerjaan,
                'status_pembayaran' => $statusPembayaran,
                'total_barang' => $totalBarang,
                'total_jasa' => $totalJasa,
                'total_keseluruhan' => $totalKeseluruhan,
                'total_sudah_dibayar' => $this->total_sudah_dibayar + $this->jumlah_dibayar_sekarang,
                'sisa_pembayaran' => $sisaPembayaran,
                'jatuh_tempo' => ($statusPembayaran !== 'lunas') ? $this->jatuh_tempo : null,
                'no_surat_pesanan' => $this->no_surat_pesanan ?: null,
                'keterangan_pembayaran' => $this->keterangan_pembayaran ?: null,
            ]);

            Log::info('TransaksiService created', ['id' => $transaksiService->id, 'invoice' => $transaksiService->invoice]);

            // 4. Create ServiceBarangItems and reduce stock using FIFO
            foreach ($this->itemsBarang as $item) {
                $usedPembelians = $this->reduceStockFifo($item['barang_id'], $item['jumlah']);
                
                foreach ($usedPembelians as $used) {
                    ServiceBarangItem::create([
                        'transaksi_service_id' => $transaksiService->id,
                        'pembelian_id' => $used['pembelian_id'],
                        'barang_id' => $item['barang_id'],
                        'jumlah' => $used['jumlah'],
                        'harga_jual' => $item['harga_jual'],
                        'subtotal' => $used['jumlah'] * $item['harga_jual'],
                    ]);
                }
            }

            Log::info('ServiceBarangItems created', ['count' => count($this->itemsBarang)]);

            // 5. Create ServiceJasaItems
            foreach ($this->itemsJasa as $jasa) {
                ServiceJasaItem::create([
                    'transaksi_service_id' => $transaksiService->id,
                    'nama_jasa' => $jasa['nama_jasa'],
                    'harga_jasa' => $jasa['harga_jasa'],
                    'subtotal' => $jasa['subtotal'],
                    'keterangan' => $jasa['keterangan'] ?? null,
                ]);
            }

            Log::info('ServiceJasaItems created', ['count' => count($this->itemsJasa)]);

            // 6. Create payment record if there's a payment
            if ($this->jumlah_dibayar_sekarang > 0) {
                ServicePayment::create([
                    'transaksi_service_id' => $transaksiService->id,
                    'tanggal_bayar' => now()->toDateString(),
                    'jumlah_bayar' => $this->jumlah_dibayar_sekarang,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'keterangan' => $this->keterangan_pembayaran ?: 'Pembayaran awal',
                    'kasir' => $this->kasir,
                ]);
            }

            // Commit transaction
            DB::commit();

            // Success message with detailed status
            $statusText = $this->getStatusText($statusPembayaran, $this->strategi_pembayaran, $this->status_pekerjaan, $sisaPembayaran);

            $successMessage = 'Transaksi service berhasil disimpan!' . 
                ' | Invoice: ' . $transaksiService->invoice . 
                ' | Total: Rp' . number_format($transaksiService->total_keseluruhan, 0, ',', '.') .
                $statusText;

            session()->flash('message', $successMessage);

            Log::info('Transaction saved successfully', [
                'invoice' => $transaksiService->invoice,
                'total' => $transaksiService->total_keseluruhan,
                'status_pembayaran' => $statusPembayaran,
                'status_pekerjaan' => $this->status_pekerjaan,
                'strategi_pembayaran' => $this->strategi_pembayaran
            ]);

            // Emit success event
            $this->dispatch('transaction-saved', [
                'invoice' => $transaksiService->invoice,
                'total' => $transaksiService->total_keseluruhan,
                'status_pembayaran' => $statusPembayaran,
                'status_pekerjaan' => $this->status_pekerjaan,
                'strategi_pembayaran' => $this->strategi_pembayaran
            ]);

            // Reset loading state
            $this->isSaving = false;
            $this->isLoading = false;

            // Redirect to invoice page or transactions list
            return redirect()->route('service.invoice', ['id' => $transaksiService->id]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $this->isSaving = false;
            $this->isLoading = false;
            
            Log::error('Validation error in simpanTransaksi', ['errors' => $e->errors()]);
            $this->dispatch('transaction-error', ['message' => 'Data tidak valid, periksa kembali form']);
            throw $e;
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->isSaving = false;
            $this->isLoading = false;
            
            $errorMessage = 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage();
            
            Log::error('Error saving service transaction: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'data' => [
                    'pelanggan' => $this->nama_pelanggan,
                    'nopol' => $this->nopol,
                    'total_barang' => count($this->itemsBarang),
                    'total_jasa' => count($this->itemsJasa),
                    'total_keseluruhan' => $this->getTotalKeseluruhanProperty(),
                ]
            ]);
            
            $this->addError('general', $errorMessage);
            $this->dispatch('transaction-error', ['message' => $errorMessage]);
        } finally {
            // Always reset saving state
            $this->isSaving = false;
            $this->isLoading = false;
        }
    }

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
            case 'belum_bayar':
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

    private function generateInvoice()
    {
        $prefix = 'FJS';
        $month = strtoupper(now()->format('M')); // e.g., APR
        $year = now()->format('Y');              // e.g., 2025

        try {
            // Ambil invoice terakhir di bulan dan tahun ini
            $lastInvoice = TransaksiService::where('invoice', 'like', '%/' . $prefix . '/' . $month . '/' . $year)
                ->orderBy('invoice', 'desc')
                ->first();

            if ($lastInvoice) {
                // Ambil 3 digit pertama sebagai nomor urut
                $lastNumber = intval(substr($lastInvoice->invoice, 0, 3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1; // Reset ke 001 jika bulan ini belum ada invoice
            }

            // Format ke 3 digit
            $numberFormatted = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // Gabungkan hasil akhir
            return "{$numberFormatted}/{$prefix}/{$month}/{$year}";

        } catch (\Exception $e) {
            Log::error('Gagal generate invoice: ' . $e->getMessage());

            // Fallback: tetap buat invoice darurat
            $fallbackNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            return "{$fallbackNumber}/{$prefix}/{$month}/{$year}";
        }
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