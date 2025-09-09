<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\TransaksiService;
use App\Models\ServiceBarangItem;
use App\Models\ServiceJasaItem;
use App\Models\ServicePayment;
use App\Models\Barang;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class RiwayatService extends Component
{
    use WithPagination, WithFileUploads;

    // Filter properties
    public $activeTab = 'barang';
    public $selectedBarangItems = [];
    public $manualItems = [];
    public $jasaItems = [];
    public $search = '';
    public $statusFilter = '';
    public $paymentFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $showDetailModal = false;
    public $showEditModal = false;
    public $showAddItemModal = false;
    public $showPaymentModal = false;
    public $showDeleteConfirmModal = false;
    public $showEditItemModal = false;
    public $showDeleteItemConfirmModal = false;
    public $showAddManualItemModal = false;

    // Selected transaction
    public $selectedTransaction = null;
    public $selectedTransactionId = null;

    // Edit properties
    public $editStatusPekerjaan = '';
    public $editStatusPembayaran = '';
    public $editDiagnosa = '';
    public $editPekerjaanDilakukan = '';
    public $editKeterangan = '';

    // Add item properties
    public $availableBarangs = [];
    public $selectedBarangId = '';
    public $itemJumlah = 1;
    public $itemHargaJual = 0;
    public $itemType = 'barang'; // 'barang', 'jasa', or 'manual'
    public $suggestedPrice = 0;

    // Service properties for adding - FIXED NAMES
    public $namaJasaBaru = '';
    public $hargaJasaBaru = 0;
    public $keteranganJasaBaru = '';

    // Manual item properties - Updated to match form
    public $nama_barang_manual = '';
    public $jumlah_manual = 1;
    public $satuan_manual = 'pcs';
    public $harga_jual_manual = 0;
    public $harga_beli_manual = 0;

    // Edit item properties
    public $editingItem = null;
    public $editingItemType = '';
    public $editItemJumlah = 1;
    public $editItemHargaJual = 0;
    public $editItemHargaBeli = 0;
    public $editNamaJasa = '';
    public $editHargaJasa = 0;
    public $editKeteranganJasa = '';
    
    // Edit manual item properties
    public $editNamaBarangManual = '';
    public $editSatuanManual = 'pcs';
    public $editKeteranganManual = '';
    
    public $itemToDelete = null;
    public $itemToDeleteType = '';

    // Payment properties
    public $jumlahBayar = 0;
    public $metodePembayaran = 'tunai';
    public $keteranganPembayaran = '';
    public $tanggalBayar = '';
    public $buktiPembayaran = null;

    // Delete confirmation
    public $transactionToDelete = null;

    // Payment detail properties
    public $showPaymentDetailModal = false;
    public $selectedPayment = null;
    public $editingPayment = null;
    public $editPaymentAmount = 0;
    public $editPaymentMethod = 'tunai';
    public $editPaymentDate = '';
    public $editPaymentNote = '';
    public $editPaymentProof = null;
    public $currentPaymentProof = null;

    public $editingDiscount = false;
    public $editDiskon = 0;
    public $editTipeDiskon = 'nominal';
    public $showDiscountModal = false;

    public $manualDiskon = 0;
    public $manualTipeDiskon = 'nominal';
    public $applyDiscountToPayment = false;

    protected $rules = [
        'editStatusPekerjaan' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
        'editStatusPembayaran' => 'required|in:belum,sebagian,lunas',
        'editDiagnosa' => 'nullable|string',
        'editPekerjaanDilakukan' => 'nullable|string',
        'editKeterangan' => 'nullable|string',
        'selectedBarangId' => 'required_if:itemType,barang|exists:barangs,id',
        'itemJumlah' => 'required_if:itemType,barang|integer|min:1',
        'itemHargaJual' => 'required_if:itemType,barang|numeric|min:0',
        
        // FIXED: Proper jasa validation rules
        'namaJasaBaru' => 'required_when:activeTab,jasa|string|max:255',
        'hargaJasaBaru' => 'required_when:activeTab,jasa|numeric|min:0',
        'keteranganJasaBaru' => 'nullable|string|max:500',
        
        // Manual item rules
        'nama_barang_manual' => 'required_when:activeTab,manual|string|max:255',
        'jumlah_manual' => 'required_when:activeTab,manual|integer|min:1',
        'satuan_manual' => 'required_when:activeTab,manual|string|max:20',
        'harga_jual_manual' => 'required_when:activeTab,manual|numeric|min:0',
        'harga_beli_manual' => 'required_when:activeTab,manual|numeric|min:0',
        
        'editItemJumlah' => 'required|integer|min:1',
        'editItemHargaJual' => 'required|numeric|min:0',
        'editItemHargaBeli' => 'required|numeric|min:0',
        'editNamaJasa' => 'required|string|max:255',
        'editHargaJasa' => 'required|numeric|min:0',
        'editKeteranganJasa' => 'nullable|string',
        
        // Edit manual item rules
        'editNamaBarangManual' => 'required|string|max:255',
        'editSatuanManual' => 'required|string|max:20',
        'editKeteranganManual' => 'nullable|string',
        
        'jumlahBayar' => 'required|numeric|min:1',
        'metodePembayaran' => 'required|in:tunai,transfer',
        'tanggalBayar' => 'required|date',
        'keteranganPembayaran' => 'nullable|string',
        'buktiPembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'editPaymentAmount' => 'required|numeric|min:1',
        'editPaymentMethod' => 'required|in:tunai,transfer',
        'editPaymentDate' => 'required|date',
        'editPaymentNote' => 'nullable|string',
        'editPaymentProof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ];

    protected $messages = [
        'editStatusPekerjaan.required' => 'Status pekerjaan harus dipilih',
        'editStatusPembayaran.required' => 'Status pembayaran harus dipilih',
        'selectedBarangId.required_if' => 'Pilih barang terlebih dahulu',
        'selectedBarangId.exists' => 'Barang yang dipilih tidak valid',
        'itemJumlah.required_if' => 'Jumlah harus diisi',
        'itemJumlah.min' => 'Jumlah minimal 1',
        'itemHargaJual.required_if' => 'Harga jual harus diisi',
        'itemHargaJual.min' => 'Harga jual tidak boleh negatif',
        
        // FIXED: Jasa validation messages
        'namaJasaBaru.required_when' => 'Nama jasa harus diisi',
        'namaJasaBaru.max' => 'Nama jasa maksimal 255 karakter',
        'hargaJasaBaru.required_when' => 'Harga jasa harus diisi',
        'hargaJasaBaru.min' => 'Harga jasa tidak boleh negatif',
        'keteranganJasaBaru.max' => 'Keterangan maksimal 500 karakter',
        
        // Manual item messages
        'nama_barang_manual.required_when' => 'Nama barang manual harus diisi',
        'nama_barang_manual.max' => 'Nama barang maksimal 255 karakter',
        'jumlah_manual.required_when' => 'Jumlah barang manual harus diisi',
        'jumlah_manual.min' => 'Jumlah minimal 1',
        'satuan_manual.required_when' => 'Satuan harus dipilih',
        'harga_jual_manual.required_when' => 'Harga jual manual harus diisi',
        'harga_jual_manual.min' => 'Harga jual tidak boleh negatif',
        'harga_beli_manual.required_when' => 'Harga beli manual harus diisi',
        'harga_beli_manual.min' => 'Harga beli tidak boleh negatif',
    ];

    public function mount()
    {
        $this->dateFrom = now()->subMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->tanggalBayar = now()->format('Y-m-d');
        $this->loadAvailableBarangs();
    }

    public function loadAvailableBarangs()
    {
        $this->availableBarangs = Barang::with(['pembelians' => function($query) {
            $query->where('jumlah_tersisa', '>', 0);
        }])
        ->whereHas('pembelians', function($query) {
            $query->where('jumlah_tersisa', '>', 0);
        })
        ->get()
        ->map(function($barang) {
            $totalStok = $barang->pembelians->sum('jumlah_tersisa');
            return [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'merk' => $barang->merk,
                'tipe' => $barang->tipe,
                'stok' => $totalStok,
                'avg_hpp' => $barang->pembelians->avg('harga_beli') ?? 0
            ];
        })
        ->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // FIXED: Don't force price, just suggest it
    public function updatedSelectedBarangId($value)
    {
        if ($value) {
            $barang = collect($this->availableBarangs)->firstWhere('id', $value);
            if ($barang) {
                // Set suggested price but don't force it
                $this->suggestedPrice = round($barang['avg_hpp'] * 1.3);
                
                // Only set itemHargaJual if it's currently 0 (not set by user)
                if ($this->itemHargaJual == 0) {
                    $this->itemHargaJual = $this->suggestedPrice;
                }
            }
        } else {
            $this->suggestedPrice = 0;
        }
    }

    public function updatedJumlahBayar($value)
    {
        if ($this->selectedTransaction && $value > $this->selectedTransaction->sisa_pembayaran) {
            $this->jumlahBayar = $this->selectedTransaction->sisa_pembayaran;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->paymentFilter = '';
        $this->dateFrom = now()->subMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

public function showDetail($transactionId)
    {
        $this->selectedTransaction = TransaksiService::with([
            'pelangganMobil',
            'serviceBarangItems.barang',
            'serviceBarangItems.pembelian',
            'serviceJasaItems',
        ])->find($transactionId);

        if ($this->selectedTransaction) {
            $this->showDetailModal = true;
        }
    }

    public function showEdit($transactionId)
    {
        $this->selectedTransaction = TransaksiService::with([
            'pelangganMobil',
            'serviceBarangItems.barang',
            'serviceJasaItems',
            'servicePayments' => function($query) {

            }
        ])->find($transactionId);

        if ($this->selectedTransaction) {
            $this->selectedTransactionId = $transactionId;
            $this->editStatusPekerjaan = $this->selectedTransaction->status_pekerjaan;
            $this->editStatusPembayaran = $this->selectedTransaction->status_pembayaran;
            $this->editDiagnosa = $this->selectedTransaction->diagnosa;
            $this->editPekerjaanDilakukan = $this->selectedTransaction->pekerjaan_dilakukan;
            $this->editKeterangan = $this->selectedTransaction->keterangan_pembayaran;
            $this->showEditModal = true;
        }
    }

    public function openPaymentDetail($paymentId)
    {
        $this->resetPaymentForm(); // untuk reset input dan flag
        $this->selectedPayment = ServicePayment::findOrFail($paymentId);
        $this->editingPayment = false;
        $this->showPaymentDetailModal = true;
    }

    public function editPayment($paymentId)
    {
        $payment = ServicePayment::findOrFail($paymentId);

        $this->selectedPayment = $payment;
        $this->editingPayment = true;
        $this->showPaymentDetailModal = true;

        $this->editPaymentAmount = $payment->jumlah_bayar;
        $this->editPaymentDate = $payment->tanggal_bayar;
        $this->editPaymentMethod = $payment->metode_pembayaran;
        $this->editPaymentNote = $payment->keterangan;
        $this->currentPaymentProof = $payment->bukti_bayar;
    }

    public function resetModalStates()
    {
        $this->reset([
            'showDetailModal',
            'showEditModal',
            'showAddItemModal',
            'showEditItemModal',
            'showDeleteItemModal',
            'showAddPaymentModal',
            'showEditPaymentModal',
            'showDeletePaymentModal',
            'selectedTransaction',
            'selectedTransactionId',
            'editStatusPekerjaan',
            'editStatusPembayaran',
            'editDiagnosa',
            'editPekerjaanDilakukan',
            'editKeterangan',
        ]);
    }


    public function resetPaymentForm()
    {
        $this->editingPayment = false;
        $this->editPaymentAmount = null;
        $this->editPaymentDate = null;
        $this->editPaymentMethod = null;
        $this->editPaymentNote = null;
        $this->editPaymentProof = null;
        $this->currentPaymentProof = null;
        $this->selectedPayment = null;
        $this->showPaymentDetailModal = false;
    }

    public function showAddItem($transactionId)
    {
        $this->selectedTransactionId = $transactionId;
        $this->selectedTransaction = TransaksiService::find($transactionId);
        $this->resetAddItemForm();
        $this->loadAvailableBarangs();
        $this->showAddItemModal = true;
    }

    public function showPayment($transactionId)
    {
        $this->selectedTransactionId = $transactionId;
        $this->selectedTransaction = TransaksiService::with(['servicePayments' => function($query) {
            // FIXED: Sort by creation date, then by payment date
            $query->orderBy('id', 'desc')
                  ->orderBy('tanggal_bayar', 'desc');
        }, 'pelangganMobil'])
            ->find($transactionId);

        if ($this->selectedTransaction) {
            $sisaPembayaran = $this->selectedTransaction->sisa_pembayaran;
            $this->jumlahBayar = $sisaPembayaran > 0 ? $sisaPembayaran : 0;
            $this->metodePembayaran = $this->selectedTransaction->metode_pembayaran ?? 'tunai';
            $this->keteranganPembayaran = '';
            $this->tanggalBayar = now()->format('Y-m-d');
            $this->buktiPembayaran = null;
            $this->showPaymentModal = true;
        }
    }

    public function confirmDelete($transactionId)
    {
        $this->transactionToDelete = $transactionId;
        $this->showDeleteConfirmModal = true;
    }

    // NEW: Edit item functions
    public function editBarangItem($itemId)
    {
        $item = ServiceBarangItem::with('barang')->find($itemId);
        if ($item) {
            $this->editingItem = $item;
            $this->editingItemType = 'barang';
            $this->editItemJumlah = $item->jumlah;
            $this->editItemHargaJual = $item->harga_jual;
            $this->editItemHargaBeli = $item->harga_beli_manual;
            $this->showEditItemModal = true;
        }
    }

    public function editJasaItem($itemId)
    {
        $item = ServiceJasaItem::find($itemId);
        if ($item) {
            $this->editingItem = $item;
            $this->editingItemType = 'jasa';
            $this->editNamaJasa = $item->nama_jasa;
            $this->editHargaJasa = $item->harga_jasa;
            $this->editKeteranganJasa = $item->keterangan;
            $this->showEditItemModal = true;
        }
    }

    public function updateItem()
    {
        if ($this->editingItemType === 'barang') {
            $this->validate([
                'editItemJumlah' => 'required|integer|min:1',
                'editItemHargaJual' => 'required|numeric|min:0',
            ]);
        } else {
            $this->validate([
                'editNamaJasa' => 'required|string|max:255',
                'editHargaJasa' => 'required|numeric|min:0',
                'editKeteranganJasa' => 'nullable|string',
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->editingItemType === 'barang') {
                $item = ServiceBarangItem::find($this->editingItem->id);
                $oldJumlah = $item->jumlah;
                $newJumlah = $this->editItemJumlah;
                $selisihJumlah = $newJumlah - $oldJumlah;

                // Check stock if increasing quantity
                if ($selisihJumlah > 0) {
                    $availableStock = Pembelian::where('barang_id', $item->barang_id)
                        ->where('jumlah_tersisa', '>', 0)
                        ->sum('jumlah_tersisa');

                    if ($selisihJumlah > $availableStock) {
                        throw new \Exception("Stok tidak mencukupi. Tersedia: {$availableStock}");
                    }

                    // Reduce additional stock using FIFO
                    $this->reduceStockFifos($item->barang_id, $selisihJumlah);
                } elseif ($selisihJumlah < 0) {
                    // Return stock if decreasing quantity
                    $returnAmount = abs($selisihJumlah);
                    $pembelian = Pembelian::find($item->pembelian_id);
                    if ($pembelian) {
                        $pembelian->increment('jumlah_tersisa', $returnAmount);
                    }
                }

                // Update item
                $item->update([
                    'jumlah' => $this->editItemJumlah,
                    'harga_jual' => $this->editItemHargaJual,
                    'harga_beli_manual' => $this->editItemHargaBeli,
                    'subtotal' => $this->editItemJumlah * $this->editItemHargaJual,
                ]);

            } else {
                // Update jasa item
                $item = ServiceJasaItem::find($this->editingItem->id);
                $item->update([
                    'nama_jasa' => $this->editNamaJasa,
                    'harga_jasa' => $this->editHargaJasa,
                    'subtotal' => $this->editHargaJasa,
                    'keterangan' => $this->editKeteranganJasa,
                ]);
            }

            // Recalculate transaction totals
            $transaction = TransaksiService::find($this->selectedTransaction->id);
            $this->recalculateTransactionTotals($transaction);

            DB::commit();

            // Refresh selected transaction
            $this->selectedTransaction->refresh();
            $this->selectedTransaction->load([
                'serviceBarangItems.barang',
                'serviceJasaItems',
                'servicePayments' => function($query) {
                    $query->orderBy('id', 'desc')
                          ->orderBy('tanggal_bayar', 'desc');
                }
            ]);

            $this->dispatch('item-updated', [
                'type' => $this->editingItemType,
                'invoice' => $transaction->invoice
            ]);

            $this->closeModal();
            session()->flash('message', 'Item berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating item: ' . $e->getMessage());
            $this->addError('general', 'Gagal mengupdate item: ' . $e->getMessage());
        }
    }

    public function confirmDeleteItem($itemId, $type)
    {
        $this->itemToDelete = $itemId;
        $this->itemToDeleteType = $type;
        $this->showDeleteItemConfirmModal = true;
    }

    public function deleteItem()
    {
        if (!$this->itemToDelete) {
            return;
        }

        try {
            DB::beginTransaction();

            if ($this->itemToDeleteType === 'barang') {
                $item = ServiceBarangItem::find($this->itemToDelete);
                if ($item) {
                    // Return stock to pembelian
                    if (!$item->is_manual && $item->pembelian_id) {
                        $pembelian = Pembelian::find($item->pembelian_id);
                        if ($pembelian) {
                            
                        }
                    }

                    Log::info('Barang item deleted', [
                        'item_id' => $item->id,
                        'barang_id' => $item->barang_id,
                        'jumlah_returned' => $item->jumlah,
                        'subtotal_removed' => $item->subtotal
                    ]);

                    $item->delete();
                }
            } else {
                $item = ServiceJasaItem::find($this->itemToDelete);
                if ($item) {
                    Log::info('Jasa item deleted', [
                        'item_id' => $item->id,
                        'nama_jasa' => $item->nama_jasa,
                        'subtotal_removed' => $item->subtotal
                    ]);

                    $item->delete();
                }
            }

            // Recalculate transaction totals
            $transaction = TransaksiService::find($this->selectedTransaction->id);
            $this->recalculateTransactionTotals($transaction);

            DB::commit();

            // Refresh selected transaction
            $this->selectedTransaction->refresh();
            $this->selectedTransaction->load([
                'serviceBarangItems.barang',
                'serviceJasaItems',
                'servicePayments' => function($query) {
                    $query->orderBy('id', 'desc')
                          ->orderBy('tanggal_bayar', 'desc');
                }
            ]);

            $this->dispatch('item-deleted', [
                'type' => $this->itemToDeleteType,
                'invoice' => $transaction->invoice
            ]);

            $this->closeModal();
            session()->flash('message', 'Item berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting item: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function updateTransaction()
    {
        $this->validate([
            'editStatusPekerjaan' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
            'editStatusPembayaran' => 'required|in:belum,sebagian,lunas',
        ]);

        try {
            DB::beginTransaction();

            $transaction = TransaksiService::find($this->selectedTransactionId);
            if (!$transaction) {
                throw new \Exception('Transaksi tidak ditemukan');
            }

            $changes = [];

            // Track changes for logging
            if ($transaction->status_pekerjaan !== $this->editStatusPekerjaan) {
                $changes['status_pekerjaan'] = [
                    'from' => $transaction->status_pekerjaan,
                    'to' => $this->editStatusPekerjaan
                ];
            }

            if ($transaction->status_pembayaran !== $this->editStatusPembayaran) {
                $changes['status_pembayaran'] = [
                    'from' => $transaction->status_pembayaran,
                    'to' => $this->editStatusPembayaran
                ];
            }

            // Update transaction
            $transaction->update([
                'status_pekerjaan' => $this->editStatusPekerjaan,
                'status_pembayaran' => $this->editStatusPembayaran,
                'diagnosa' => $this->editDiagnosa,
                'pekerjaan_dilakukan' => $this->editPekerjaanDilakukan,
                'keterangan_pembayaran' => $this->editKeterangan,
                'updated_at' => now()
            ]);

            // If status pembayaran changed to lunas, update sisa_pembayaran
            if ($this->editStatusPembayaran === 'lunas') {
                $transaction->update(['sisa_pembayaran' => 0]);
            }

            DB::commit();

            // Log the changes
            if (!empty($changes)) {
                Log::info('Transaction updated', [
                    'invoice' => $transaction->invoice,
                    'user' => Auth::user()->name,
                    'changes' => $changes
                ]);
            }

            $this->dispatch('transaction-updated', [
                'invoice' => $transaction->invoice,
                'changes' => $changes
            ]);

            $this->closeModal();
            session()->flash('message', 'Transaksi berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating transaction: ' . $e->getMessage());
            $this->addError('general', 'Gagal mengupdate transaksi: ' . $e->getMessage());
        }
    }

    public function addItemToTransaction()
    {
        if (!empty($this->selectedBarangItems) || !empty($this->manualItems) || !empty($this->jasaItems)) {
        return $this->saveAllItemsToTransaction();
    }
        // Validate transaction exists first
        if (!$this->selectedTransactionId) {
            $this->addError('general', 'Transaction ID is required');
            return;
        }

        $transaction = TransaksiService::find($this->selectedTransactionId);
        if (!$transaction) {
            $this->addError('general', 'Transaksi tidak ditemukan');
            return;
        }

        // FIXED: Use separate validation rules for each item type
        if ($this->itemType === 'barang') {
            $this->validate([
                'selectedBarangId' => 'required|exists:barangs,id',
                'itemJumlah' => 'required|integer|min:1',
                'itemHargaJual' => 'required|numeric|min:0',
            ], [
                'selectedBarangId.required' => 'Pilih barang terlebih dahulu',
                'selectedBarangId.exists' => 'Barang yang dipilih tidak valid',
                'itemJumlah.required' => 'Jumlah harus diisi',
                'itemJumlah.min' => 'Jumlah minimal 1',
                'itemHargaJual.required' => 'Harga jual harus diisi',
                'itemHargaJual.min' => 'Harga jual tidak boleh negatif',
            ]);
        } elseif ($this->itemType === 'manual') {
            $this->validate([
                'nama_barang_manual' => 'required|string|max:255',
                'jumlah_manual' => 'required|integer|min:1',
                'satuan_manual' => 'required|string|max:20',
                'harga_jual_manual' => 'required|numeric|min:0',
                'harga_beli_manual' => 'required|numeric|min:0',
            ], [
                'nama_barang_manual.required' => 'Nama barang manual harus diisi',
                'nama_barang_manual.max' => 'Nama barang maksimal 255 karakter',
                'jumlah_manual.required' => 'Jumlah barang manual harus diisi',
                'jumlah_manual.min' => 'Jumlah minimal 1',
                'satuan_manual.required' => 'Satuan harus dipilih',
                'harga_jual_manual.required' => 'Harga jual manual harus diisi',
                'harga_jual_manual.min' => 'Harga jual tidak boleh negatif',
                'harga_beli_manual.required' => 'Harga beli manual harus diisi',
                'harga_beli_manual.min' => 'Harga beli tidak boleh negatif',
            ]);
        } else { // jasa
            $this->validate([
                'namaJasaBaru' => 'required|string|max:255',
                'hargaJasaBaru' => 'required|numeric|min:0',
                'keteranganJasaBaru' => 'nullable|string',
            ], [
                'namaJasaBaru.required' => 'Nama jasa harus diisi',
                'hargaJasaBaru.required' => 'Harga jasa harus diisi',
                'hargaJasaBaru.min' => 'Harga jasa tidak boleh negatif',
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->itemType === 'barang') {
                // Check stock availability
                $availableStock = Pembelian::where('barang_id', $this->selectedBarangId)
                    ->where('jumlah_tersisa', '>', 0)
                    ->sum('jumlah_tersisa');

                if ($this->itemJumlah > $availableStock) {
                    throw new \Exception("Stok tidak mencukupi. Tersedia: {$availableStock}");
                }

                // Reduce stock using FIFO
                $usedPembelians = $this->reduceStockFifo($this->selectedBarangId, $this->itemJumlah);

                // Create service barang items
                foreach ($usedPembelians as $used) {
                    ServiceBarangItem::create([
                        'transaksi_service_id' => $this->selectedTransactionId,
                        'pembelian_id' => $used['pembelian_id'],
                        'barang_id' => $this->selectedBarangId,
                        'jumlah' => $used['jumlah'],
                        'harga_jual' => $this->itemHargaJual,
                        'subtotal' => $used['jumlah'] * $this->itemHargaJual,
                        'is_manual' => false,
                        'nama_barang_manual' => null,
                        'satuan' => null,
                    ]);
                }

                Log::info('Barang item added to transaction', [
                    'invoice' => $transaction->invoice,
                    'barang_id' => $this->selectedBarangId,
                    'jumlah' => $this->itemJumlah,
                    'harga_jual' => $this->itemHargaJual,
                    'subtotal' => $this->itemJumlah * $this->itemHargaJual
                ]);

            } elseif ($this->itemType === 'manual') {
                // FIXED: Additional validation for manual items
                if (empty(trim($this->nama_barang_manual))) {
                    throw new \Exception('Nama barang manual harus diisi');
                }
                if ($this->jumlah_manual <= 0) {
                    throw new \Exception('Jumlah harus lebih dari 0');
                }
                if ($this->harga_jual_manual < 0) {
                    throw new \Exception('Harga jual tidak boleh negatif');
                }

                $subtotal = $this->jumlah_manual * $this->harga_jual_manual;
                
                // FIXED: Create manual item with proper nullable fields
                $manualItem = ServiceBarangItem::create([
                    'transaksi_service_id' => $this->selectedTransactionId, // Required field
                    'barang_id' => null, // NULL for manual items
                    'pembelian_id' => null, // NULL for manual items  
                    'nama_barang_manual' => trim($this->nama_barang_manual),
                    'jumlah' => $this->jumlah_manual,
                    'satuan' => $this->satuan_manual ?: 'pcs',
                    'harga_jual' => $this->harga_jual_manual,
                    'harga_beli_manual' => $this->harga_beli_manual,
                    'subtotal' => $subtotal,
                    'is_manual' => true, // Required flag for manual items
                ]);

                Log::info('Manual item added to transaction', [
                    'invoice' => $transaction->invoice,
                    'item_id' => $manualItem->id,
                    'nama_barang_manual' => $this->nama_barang_manual,
                    'jumlah' => $this->jumlah_manual,
                    'satuan' => $this->satuan_manual,
                    'harga_jual' => $this->harga_jual_manual,
                    'subtotal' => $subtotal
                ]);

            } else { // jasa
                // Add service/jasa
                ServiceJasaItem::create([
                    'transaksi_service_id' => $this->selectedTransactionId,
                    'nama_jasa' => $this->namaJasaBaru,
                    'harga_jasa' => $this->hargaJasaBaru,
                    'subtotal' => $this->hargaJasaBaru,
                    'keterangan' => $this->keteranganJasaBaru
                ]);

                Log::info('Jasa item added to transaction', [
                    'invoice' => $transaction->invoice,
                    'nama_jasa' => $this->namaJasaBaru,
                    'harga_jasa' => $this->hargaJasaBaru,
                    'subtotal' => $this->hargaJasaBaru
                ]);
            }

            // Recalculate all totals from database
            $this->recalculateTransactionTotals($transaction);

            DB::commit();

            $this->dispatch('item-added', [
                'invoice' => $transaction->invoice,
                'type' => $this->itemType,
                'amount' => $this->itemType === 'barang' ? 
                    ($this->itemJumlah * $this->itemHargaJual) : 
                    ($this->itemType === 'manual' ? 
                        ($this->jumlah_manual * $this->harga_jual_manual) : 
                        $this->hargaJasaBaru)
            ]);

            $this->closeModal();
            $this->loadAvailableBarangs(); // Refresh stock data
            session()->flash('message', 'Item berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding item to transaction: ' . $e->getMessage(), [
                'transaction_id' => $this->selectedTransactionId,
                'item_type' => $this->itemType,
                'manual_item_data' => $this->itemType === 'manual' ? [
                    'nama_barang_manual' => $this->nama_barang_manual,
                    'jumlah_manual' => $this->jumlah_manual,
                    'satuan_manual' => $this->satuan_manual,
                    'harga_jual_manual' => $this->harga_jual_manual,
                ] : null,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('general', 'Gagal menambah item: ' . $e->getMessage());
        }
    }

    public function addManualItemToTransaction()
    {
        $this->itemType = 'manual';
        $this->addItemToTransaction();
    }

    /**
     * Recalculate transaction totals to ensure accuracy
     */
    private function recalculateTransactionTotals($transaction)
    {
        // Recalculate total barang
        $totalBarang = ServiceBarangItem::where('transaksi_service_id', $transaction->id)
            ->sum('subtotal');

        // Recalculate total jasa
        $totalJasa = ServiceJasaItem::where('transaksi_service_id', $transaction->id)
            ->sum('subtotal');

        // Calculate subtotal before discount
        $subtotalSebelumDiskon = $totalBarang + $totalJasa;

        $diskonAmount = 0;
        if ($transaction->diskon > 0) {
            if ($transaction->tipe_diskon === 'persentase') {
                $diskonAmount = ($subtotalSebelumDiskon * $transaction->diskon) / 100;
            } else {
                $diskonAmount = $transaction->diskon;
            }
        }

        // Calculate total after discount
        $totalKeseluruhan = $subtotalSebelumDiskon - $diskonAmount;

        // Recalculate total sudah dibayar
        $totalSudahDibayar = ServicePayment::where('transaksi_service_id', $transaction->id)
            ->sum('jumlah_bayar');

        // Calculate sisa pembayaran
        $sisaPembayaran = $totalKeseluruhan - $totalSudahDibayar;

        // Determine payment status
        $statusPembayaran = 'belum';
        if ($sisaPembayaran <= 0) {
            $statusPembayaran = 'lunas';
            $sisaPembayaran = 0;
        } elseif ($totalSudahDibayar > 0) {
            $statusPembayaran = 'sebagian';
        }

        // Update transaction with recalculated values
        $transaction->update([
            'total_barang' => $totalBarang,
            'total_jasa' => $totalJasa,
            'subtotal_sebelum_diskon' => $subtotalSebelumDiskon, // Added subtotal before discount
            'diskon_amount' => $diskonAmount, // Added discount amount
            'total_keseluruhan' => $totalKeseluruhan,
            'total_sudah_dibayar' => $totalSudahDibayar,
            'sisa_pembayaran' => $sisaPembayaran,
            'status_pembayaran' => $statusPembayaran,
        ]);

        Log::info('Transaction totals recalculated with discount', [
            'invoice' => $transaction->invoice,
            'total_barang' => $totalBarang,
            'total_jasa' => $totalJasa,
            'subtotal_sebelum_diskon' => $subtotalSebelumDiskon,
            'diskon' => $transaction->diskon,
            'tipe_diskon' => $transaction->tipe_diskon,
            'diskon_amount' => $diskonAmount,
            'total_keseluruhan' => $totalKeseluruhan,
            'total_sudah_dibayar' => $totalSudahDibayar,
            'sisa_pembayaran' => $sisaPembayaran,
            'status_pembayaran' => $statusPembayaran
        ]);
    }

    public function showEditDiscount($transactionId)
    {
        $this->selectedTransaction = TransaksiService::find($transactionId);
        
        if ($this->selectedTransaction) {
            $this->selectedTransactionId = $transactionId;
            $this->editDiskon = $this->selectedTransaction->diskon ?? 0;
            $this->editTipeDiskon = $this->selectedTransaction->tipe_diskon ?? 'nominal';
            $this->editingDiscount = true;
            $this->showDiscountModal = true;
        }
    }

    public function updateDiscount()
    {
        $this->validate([
            'editDiskon' => 'required|numeric|min:0',
            'editTipeDiskon' => 'required|in:nominal,persentase',
        ], [
            'editDiskon.required' => 'Diskon wajib diisi.',
            'editDiskon.numeric' => 'Diskon harus berupa angka.',
            'editDiskon.min' => 'Diskon tidak boleh negatif.',
        ]);

        // Validate percentage discount
        if ($this->editTipeDiskon === 'persentase' && $this->editDiskon > 100) {
            $this->addError('editDiskon', 'Diskon persentase tidak boleh lebih dari 100%.');
            return;
        }

        try {
            DB::beginTransaction();

            $transaction = TransaksiService::findOrFail($this->selectedTransactionId);
            
            // Update discount
            $transaction->update([
                'diskon' => $this->editDiskon,
                'tipe_diskon' => $this->editTipeDiskon,
            ]);

            // Recalculate totals with new discount
            $this->recalculateTransactionTotals($transaction);

            DB::commit();

            // Refresh the selected transaction
            $this->selectedTransaction->refresh();
            $this->selectedTransaction->load(['servicePayments', 'pelangganMobil']);

            $this->showDiscountModal = false;
            $this->editingDiscount = false;

            $this->dispatch('discount-updated', [
                'invoice' => $transaction->invoice,
                'diskon' => $this->editDiskon,
                'tipe_diskon' => $this->editTipeDiskon,
            ]);

            session()->flash('message', 'Diskon berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating discount: ' . $e->getMessage());
            $this->addError('editDiskon', 'Gagal memperbarui diskon: ' . $e->getMessage());
        }
    }

    public function addPayment()
    {
        $this->validate([
            'tanggalBayar' => 'required|date',
            'jumlahBayar' => 'required|numeric|min:1',
            'metodePembayaran' => 'required|in:tunai,transfer',
            'manualDiskon' => 'nullable|numeric|min:0',
            'manualTipeDiskon' => 'required_with:manualDiskon|in:nominal,persentase',
        ], [
            'tanggalBayar.required' => 'Tanggal bayar wajib diisi.',
            'jumlahBayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlahBayar.min' => 'Jumlah bayar minimal Rp 1.',
            'metodePembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'manualDiskon.numeric' => 'Diskon harus berupa angka.',
            'manualDiskon.min' => 'Diskon tidak boleh negatif.',
        ]);

        // Validate percentage discount
        if ($this->applyDiscountToPayment && $this->manualTipeDiskon === 'persentase' && $this->manualDiskon > 100) {
            $this->addError('manualDiskon', 'Diskon persentase tidak boleh lebih dari 100%.');
            return;
        }

        try {
            DB::beginTransaction();

            $transaction = TransaksiService::findOrFail($this->selectedTransactionId);

            if ($this->applyDiscountToPayment && $this->manualDiskon > 0) {
                $transaction->update([
                    'diskon' => $this->manualDiskon,
                    'tipe_diskon' => $this->manualTipeDiskon,
                ]);

                // Recalculate totals with new discount
                $this->recalculateTransactionTotals($transaction);
                $transaction->refresh(); // Refresh to get updated totals
            }

            // Validate payment amount doesn't exceed remaining balance
            if ($this->jumlahBayar > $transaction->sisa_pembayaran) {
                throw new \Exception('Jumlah pembayaran melebihi sisa tagihan. Sisa: Rp' . number_format($transaction->sisa_pembayaran, 0, ',', '.'));
            }

            // DEBUGGING: Log file info before processing
            $fileInfo = null;
            if ($this->buktiPembayaran) {
                $fileInfo = [
                    'original_name' => $this->buktiPembayaran->getClientOriginalName(),
                    'size' => $this->buktiPembayaran->getSize(),
                    'mime_type' => $this->buktiPembayaran->getMimeType(),
                    'extension' => $this->buktiPembayaran->getClientOriginalExtension(),
                    'is_valid' => $this->buktiPembayaran->isValid(),
                ];
                Log::info('File info before upload', $fileInfo);
            }

            // FIXED: Handle file upload with detailed debugging
            $buktiPath = null;
            if ($this->buktiPembayaran && $this->buktiPembayaran->isValid()) {
                try {
                    // Generate unique filename
                    $extension = $this->buktiPembayaran->getClientOriginalExtension();
                    $fileName = 'bukti_' . $transaction->invoice . '_' . time() . '_' . uniqid() . '.' . $extension;
                    
                    Log::info('Attempting file upload', [
                        'invoice' => $transaction->invoice,
                        'filename' => $fileName,
                        'disk' => 'public',
                        'directory' => 'payment-proofs'
                    ]);
                    
                    // Store file
                    $buktiPath = $this->buktiPembayaran->storeAs('payment-proofs', $fileName, 'public');
                    
                    Log::info('File upload result', [
                        'bukti_path' => $buktiPath,
                        'full_path' => storage_path('app/public/' . $buktiPath),
                        'file_exists' => Storage::disk('public')->exists($buktiPath),
                        'file_size_on_disk' => Storage::disk('public')->size($buktiPath),
                        'file_url' => Storage::disk('public')->url($buktiPath)
                    ]);
                    
                    // Verify file was actually saved
                    if (!Storage::disk('public')->exists($buktiPath)) {
                        throw new \Exception('File gagal disimpan ke storage - file not found after upload');
                    }
                    
                    // Additional verification
                    $savedFileSize = Storage::disk('public')->size($buktiPath);
                    if ($savedFileSize === false || $savedFileSize === 0) {
                        throw new \Exception('File tersimpan tapi size 0 atau corrupt');
                    }
                    
                } catch (\Exception $fileException) {
                    Log::error('File upload failed', [
                        'invoice' => $transaction->invoice,
                        'error' => $fileException->getMessage(),
                        'trace' => $fileException->getTraceAsString(),
                        'file_info' => $fileInfo
                    ]);
                    throw new \Exception('Gagal mengupload bukti pembayaran: ' . $fileException->getMessage());
                }
            }

            // DEBUGGING: Log data before database insert
            $paymentData = [
                'transaksi_service_id' => $this->selectedTransactionId,
                'tanggal_bayar' => $this->tanggalBayar,
                'jumlah_bayar' => $this->jumlahBayar,
                'metode_pembayaran' => $this->metodePembayaran,
                'keterangan' => $this->keteranganPembayaran ?: 'Pembayaran tambahan',
                'bukti_bayar' => $buktiPath, // CRITICAL: Make sure this is the correct column name
                'kasir' => Auth::user()->name,
            ];

            Log::info('Payment data before insert', $paymentData);

            // FIXED: Create payment record with explicit field mapping
            $payment = new ServicePayment();
            $payment->transaksi_service_id = $this->selectedTransactionId;
            $payment->tanggal_bayar = $this->tanggalBayar;
            $payment->jumlah_bayar = $this->jumlahBayar;
            $payment->metode_pembayaran = $this->metodePembayaran;
            $payment->keterangan = $this->keteranganPembayaran ?: 'Pembayaran tambahan';
            $payment->bukti_bayar = $buktiPath; // EXPLICIT ASSIGNMENT
            $payment->kasir = Auth::user()->name;
            
            $saveResult = $payment->save();

            Log::info('Payment save result', [
                'save_success' => $saveResult,
                'payment_id' => $payment->id,
                'bukti_bayar_in_db' => $payment->bukti_bayar,
                'fresh_from_db' => $payment->fresh()->bukti_bayar
            ]);

            // Verify payment was created successfully with bukti_bayar
            if (!$payment->id) {
                throw new \Exception('Gagal menyimpan data pembayaran - no payment ID');
            }

            // DEBUGGING: Double-check what's actually in the database
            $freshPayment = ServicePayment::find($payment->id);
            Log::info('Fresh payment from DB', [
                'id' => $freshPayment->id,
                'bukti_bayar' => $freshPayment->bukti_bayar,
                'all_attributes' => $freshPayment->getAttributes()
            ]);

            // Recalculate totals after adding payment
            $this->recalculateTransactionTotals($transaction);

            DB::commit();

            // Refresh the selected transaction
            $this->selectedTransaction->refresh();
            $this->selectedTransaction->load(['servicePayments' => function($query) {
                $query->orderBy('id', 'desc')->orderBy('tanggal_bayar', 'desc');
            }, 'pelangganMobil']);

            Log::info('Payment added successfully - FINAL CHECK', [
                'invoice' => $transaction->invoice,
                'payment_id' => $payment->id,
                'bukti_bayar_final' => $payment->fresh()->bukti_bayar,
                'file_url' => $buktiPath ? Storage::disk('public')->url($buktiPath) : null
            ]);

            $message = 'Pembayaran berhasil ditambahkan! ';
            if ($transaction->fresh()->status_pembayaran === 'lunas') {
                $message .= 'Transaksi telah LUNAS.';
            } else {
                $message .= 'Sisa pembayaran: Rp' . number_format($transaction->fresh()->sisa_pembayaran, 0, ',', '.');
            }

            $this->dispatch('payment-added', [
                'invoice' => $transaction->invoice,
                'amount' => $this->jumlahBayar,
                'status' => $transaction->fresh()->status_pembayaran,
                'has_proof' => !is_null($buktiPath),
                'payment_id' => $payment->id,
                'proof_url' => $buktiPath ? Storage::disk('public')->url($buktiPath) : null
            ]);

            // Reset form but keep modal open if not fully paid
            if ($transaction->fresh()->status_pembayaran !== 'lunas') {
                $this->resetPaymentFormFields();
                $this->jumlahBayar = $transaction->fresh()->sisa_pembayaran;
            }

            session()->flash('message', $message);

            // Reset manual discount fields
            $this->manualDiskon = 0;
            $this->manualTipeDiskon = 'nominal';
            $this->applyDiscountToPayment = false;

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if transaction failed
            if (isset($buktiPath) && $buktiPath && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
                Log::info('Cleaned up orphaned file after transaction failure', ['path' => $buktiPath]);
            }
            
            Log::error('Error adding payment: ' . $e->getMessage(), [
                'transaction_id' => $this->selectedTransactionId,
                'amount' => $this->jumlahBayar,
                'has_file' => !is_null($this->buktiPembayaran),
                'debug_info' => $this->debugInfo ?? [],
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('jumlahBayar', 'Gagal menambah pembayaran: ' . $e->getMessage());
        }
        $this->closeModal();
    }

    public function closeDiscountModal()
    {
        $this->showDiscountModal = false;
        $this->editingDiscount = false;
        $this->editDiskon = 0;
        $this->editTipeDiskon = 'nominal';
        $this->resetErrorBag();
    }

    // DEBUGGING METHOD: Call this to check database structure
    public function debugDatabaseStructure()
    {
        try {
            // Check table structure
            $columns = DB::select("DESCRIBE service_payments");
            
            // Check if bukti_bayar column exists
            $buktiColumn = collect($columns)->firstWhere('Field', 'bukti_bayar');
            
            // Get sample payment record
            $samplePayment = DB::table('service_payments')->first();
            
            $this->debugInfo = [
                'table_columns' => $columns,
                'bukti_bayar_column_exists' => !is_null($buktiColumn),
                'bukti_bayar_column_details' => $buktiColumn,
                'sample_payment' => $samplePayment,
                'storage_disk_config' => config('filesystems.disks.public'),
                'storage_path' => storage_path('app/public'),
                'public_path' => public_path('storage')
            ];
            
            $this->showDebugModal = true;
            
            Log::info('Database structure debug', $this->debugInfo);
            
        } catch (\Exception $e) {
            Log::error('Debug failed: ' . $e->getMessage());
            $this->addError('debug', 'Debug gagal: ' . $e->getMessage());
        }
    }

    // FIXED: View payment proof with better error handling
    public function viewPaymentProof($paymentId)
    {
        try {
            $payment = ServicePayment::find($paymentId);
            
            if (!$payment) {
                throw new \Exception('Payment record not found');
            }

            Log::info('Viewing payment proof', [
                'payment_id' => $paymentId,
                'bukti_bayar_from_db' => $payment->bukti_bayar,
                'bukti_bayar_is_null' => is_null($payment->bukti_bayar),
                'bukti_bayar_is_empty' => empty($payment->bukti_bayar)
            ]);

            if (!$payment->bukti_bayar || empty($payment->bukti_bayar)) {
                session()->flash('error', 'Tidak ada bukti pembayaran untuk record ini');
                return;
            }

            // Check if file exists in storage
            if (!Storage::disk('public')->exists($payment->bukti_bayar)) {
                Log::warning('Payment proof file not found in storage', [
                    'payment_id' => $paymentId,
                    'expected_path' => $payment->bukti_bayar,
                    'full_path' => storage_path('app/public/' . $payment->bukti_bayar),
                    'file_exists_check' => file_exists(storage_path('app/public/' . $payment->bukti_bayar))
                ]);
                
                session()->flash('error', 'File bukti pembayaran tidak ditemukan di storage');
                return;
            }

            $url = Storage::disk('public')->url($payment->bukti_bayar);
            
            Log::info('Payment proof URL generated', [
                'payment_id' => $paymentId,
                'file_path' => $payment->bukti_bayar,
                'generated_url' => $url
            ]);

            $this->dispatch('open-payment-proof', [
                'url' => $url,
                'payment_id' => $paymentId,
                'file_name' => basename($payment->bukti_bayar)
            ]);

        } catch (\Exception $e) {
            Log::error('Error viewing payment proof: ' . $e->getMessage(), [
                'payment_id' => $paymentId
            ]);
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // UTILITY: Method to fix existing payments without bukti_bayar
    public function fixMissingPaymentProofs()
    {
        try {
            DB::beginTransaction();

            // Find payments where files exist in storage but bukti_bayar is null
            $paymentsToFix = ServicePayment::whereNull('bukti_bayar')
                ->orWhere('bukti_bayar', '')
                ->get();

            $fixed = 0;
            $storageFiles = Storage::disk('public')->files('payment-proofs');

            foreach ($paymentsToFix as $payment) {
                // Try to find matching file based on transaction invoice
                $transaction = TransaksiService::find($payment->transaksi_service_id);
                if (!$transaction) continue;

                $matchingFiles = array_filter($storageFiles, function($file) use ($transaction) {
                    return strpos(basename($file), $transaction->invoice) !== false;
                });

                if (!empty($matchingFiles)) {
                    $fileToAssign = reset($matchingFiles); // Get first matching file
                    $payment->update(['bukti_bayar' => $fileToAssign]);
                    $fixed++;
                    
                    Log::info('Fixed missing bukti_bayar', [
                        'payment_id' => $payment->id,
                        'assigned_file' => $fileToAssign
                    ]);
                }
            }

            DB::commit();
            
            session()->flash('message', "Berhasil memperbaiki {$fixed} payment records");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error fixing payment proofs: ' . $e->getMessage());
            session()->flash('error', 'Gagal memperbaiki: ' . $e->getMessage());
        }
    }

    public function updatePayment($paymentId)
{
    $this->validate([
        'editPaymentAmount' => 'required|numeric|min:1',
        'editPaymentMethod' => 'required|in:tunai,transfer',
        'editPaymentDate' => 'required|date',
        'editPaymentNote' => 'nullable|string',
        'editPaymentProof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    try {
        DB::beginTransaction();

        // Get payment record
        $payment = ServicePayment::find($paymentId);
        if (!$payment) {
            throw new \Exception('Data pembayaran tidak ditemukan');
        }

        // Get transaction to validate payment amount
        $transaction = TransaksiService::find($payment->transaksi_service_id);
        if (!$transaction) {
            throw new \Exception('Transaksi tidak ditemukan');
        }

        $oldAmount = $payment->jumlah_bayar;
        $newAmount = $this->editPaymentAmount;
        $amountDifference = $newAmount - $oldAmount;

        // Calculate maximum allowed payment (current payment + remaining balance)
        $maxAllowedPayment = $oldAmount + $transaction->sisa_pembayaran;
        
        if ($newAmount > $maxAllowedPayment) {
            throw new \Exception('Jumlah bayar melebihi maksimal yang diizinkan (Rp' . number_format($maxAllowedPayment, 0, ',', '.') . ')');
        }

        // DEBUGGING: Log file info before processing
        $fileInfo = null;
        if ($this->editPaymentProof) {
            $fileInfo = [
                'original_name' => $this->editPaymentProof->getClientOriginalName(),
                'size' => $this->editPaymentProof->getSize(),
                'mime_type' => $this->editPaymentProof->getMimeType(),
                'extension' => $this->editPaymentProof->getClientOriginalExtension(),
                'is_valid' => $this->editPaymentProof->isValid(),
            ];
            Log::info('File info before upload (update)', $fileInfo);
        }

        // FIXED: Handle file upload - Keep existing proof or set new one
        $buktiPath = $payment->bukti_bayar; // Keep existing proof by default (even if null)
        $oldBuktiPath = $payment->bukti_bayar; // Store old path for cleanup (can be null)
        $newFileUploaded = false;
        
        if ($this->editPaymentProof && $this->editPaymentProof->isValid()) {
            try {
                // Generate unique filename
                $extension = $this->editPaymentProof->getClientOriginalExtension();
                $fileName = 'bukti_' . $transaction->invoice . '_' . time() . '_' . uniqid() . '.' . $extension;
                
                Log::info('Attempting file upload (update)', [
                    'payment_id' => $payment->id,
                    'invoice' => $transaction->invoice,
                    'filename' => $fileName,
                    'disk' => 'public',
                    'directory' => 'payment-proofs',
                    'old_file' => $oldBuktiPath,
                    'old_file_is_null' => is_null($oldBuktiPath)
                ]);
                
                // Store new file
                $buktiPath = $this->editPaymentProof->storeAs('payment-proofs', $fileName, 'public');
                $newFileUploaded = true;
                
                Log::info('File upload result (update)', [
                    'bukti_path' => $buktiPath,
                    'full_path' => storage_path('app/public/' . $buktiPath),
                    'file_exists' => Storage::disk('public')->exists($buktiPath),
                    'file_size_on_disk' => Storage::disk('public')->size($buktiPath),
                    'file_url' => Storage::disk('public')->url($buktiPath)
                ]);
                
                // Verify file was actually saved
                if (!Storage::disk('public')->exists($buktiPath)) {
                    throw new \Exception('File gagal disimpan ke storage - file not found after upload');
                }
                
                // Additional verification
                $savedFileSize = Storage::disk('public')->size($buktiPath);
                if ($savedFileSize === false || $savedFileSize === 0) {
                    throw new \Exception('File tersimpan tapi size 0 atau corrupt');
                }
                
            } catch (\Exception $fileException) {
                Log::error('File upload failed (update)', [
                    'payment_id' => $payment->id,
                    'invoice' => $transaction->invoice,
                    'error' => $fileException->getMessage(),
                    'trace' => $fileException->getTraceAsString(),
                    'file_info' => $fileInfo
                ]);
                throw new \Exception('Gagal mengupload bukti pembayaran: ' . $fileException->getMessage());
            }
        }

        // DEBUGGING: Log data before database update
        $updateData = [
            'payment_id' => $payment->id,
            'old_amount' => $oldAmount,
            'new_amount' => $newAmount,
            'amount_difference' => $amountDifference,
            'jumlah_bayar' => $this->editPaymentAmount,
            'metode_pembayaran' => $this->editPaymentMethod,
            'tanggal_bayar' => $this->editPaymentDate,
            'keterangan' => $this->editPaymentNote ?: $payment->keterangan,
            'bukti_bayar' => $buktiPath, // Can be null, existing path, or new path
            'old_bukti_path' => $oldBuktiPath,
            'new_file_uploaded' => $newFileUploaded,
            'old_file_was_null' => is_null($oldBuktiPath)
        ];

        Log::info('Payment update data before save', $updateData);

        // FIXED: Update payment record with explicit field assignment
        // This handles cases where bukti_bayar was previously null
        $payment->jumlah_bayar = $this->editPaymentAmount;
        $payment->metode_pembayaran = $this->editPaymentMethod;
        $payment->tanggal_bayar = $this->editPaymentDate;
        $payment->keterangan = $this->editPaymentNote ?: $payment->keterangan;
        
        // CRITICAL FIX: Always set bukti_bayar, even if it was null before
        // If new file uploaded, use new path
        // If no new file but editing, keep existing (even if null)
        $payment->bukti_bayar = $buktiPath;
        
        $updateResult = $payment->save();

        Log::info('Payment update result', [
            'update_success' => $updateResult,
            'payment_id' => $payment->id,
            'bukti_bayar_in_db' => $payment->bukti_bayar,
            'fresh_from_db' => $payment->fresh()->bukti_bayar,
            'new_file_was_uploaded' => $newFileUploaded
        ]);

        // Verify payment was updated successfully
        if (!$updateResult) {
            throw new \Exception('Gagal mengupdate data pembayaran');
        }

        // DEBUGGING: Double-check what's actually in the database
        $freshPayment = ServicePayment::find($payment->id);
        Log::info('Fresh payment from DB after update', [
            'id' => $freshPayment->id,
            'bukti_bayar' => $freshPayment->bukti_bayar,
            'jumlah_bayar' => $freshPayment->jumlah_bayar,
            'bukti_bayar_changed' => $freshPayment->bukti_bayar !== $oldBuktiPath,
            'all_attributes' => $freshPayment->getAttributes()
        ]);


        // FIXED: Delete old file ONLY if:
        // 1. New file was uploaded successfully
        // 2. Old file path exists and is not null/empty
        // 3. Old path is different from new path
        // 4. Old file actually exists in storage
        if ($newFileUploaded && 
            !empty($oldBuktiPath) && 
            $oldBuktiPath !== $buktiPath && 
            Storage::disk('public')->exists($oldBuktiPath)) {
            
            $deleteResult = Storage::disk('public')->delete($oldBuktiPath);
            Log::info('Old payment proof deletion attempt', [
                'payment_id' => $payment->id,
                'deleted_path' => $oldBuktiPath,
                'new_path' => $buktiPath,
                'delete_success' => $deleteResult,
                'file_existed_before_delete' => true
            ]);
        } elseif ($newFileUploaded && !empty($oldBuktiPath)) {
            Log::info('Old payment proof NOT deleted', [
                'payment_id' => $payment->id,
                'old_path' => $oldBuktiPath,
                'new_path' => $buktiPath,
                'reason' => 'File does not exist in storage or paths are same'
            ]);
        } else {
            Log::info('No old file to delete', [
                'payment_id' => $payment->id,
                'old_path_was_null' => is_null($oldBuktiPath),
                'new_file_uploaded' => $newFileUploaded
            ]);

        }

        // Recalculate transaction totals after updating payment
        $this->recalculateTransactionTotals($transaction);

        DB::commit();

        // Refresh the selected transaction
        if (isset($this->selectedTransaction)) {
            $this->selectedTransaction->refresh();
            $this->selectedTransaction->load(['servicePayments' => function($query) {
                $query->orderBy('id', 'desc')->orderBy('tanggal_bayar', 'desc');
            }, 'pelangganMobil']);
        }

        Log::info('Payment updated successfully - FINAL CHECK', [
            'payment_id' => $payment->id,
            'old_amount' => $oldAmount,
            'new_amount' => $newAmount,
            'amount_difference' => $amountDifference,
            'bukti_bayar_final' => $payment->fresh()->bukti_bayar,
            'file_url' => $buktiPath ? Storage::disk('public')->url($buktiPath) : null,
            'transaction_status' => $transaction->fresh()->status_pembayaran,
            'proof_added_to_previously_null' => is_null($oldBuktiPath) && !is_null($buktiPath)
        ]);

        // FIXED: Enhanced success message
        $message = 'Pembayaran berhasil diupdate! ';
        
        if ($newFileUploaded) {
            if (is_null($oldBuktiPath)) {
                $message .= 'Bukti pembayaran berhasil ditambahkan. ';
            } else {
                $message .= 'Bukti pembayaran berhasil diperbarui. ';
            }
        }
        
        if ($transaction->fresh()->status_pembayaran === 'lunas') {
            $message .= 'Transaksi telah LUNAS.';
        } else {
            $message .= 'Sisa pembayaran: Rp' . number_format($transaction->fresh()->sisa_pembayaran, 0, ',', '.');
        }

        $this->dispatch('payment-updated', [
            'payment_id' => $payment->id,
            'amount_difference' => $amountDifference,
            'new_amount' => $this->editPaymentAmount,
            'old_amount' => $oldAmount,
            'status' => $transaction->fresh()->status_pembayaran,
            'has_new_proof' => $newFileUploaded,
            'proof_was_added_to_null' => is_null($oldBuktiPath) && !is_null($buktiPath),
            'proof_url' => $buktiPath ? Storage::disk('public')->url($buktiPath) : null
        ]);

        $this->closeModal();
        session()->flash('message', $message);

    } catch (\Exception $e) {
        DB::rollback();
        
        // FIXED: Clean up uploaded file if transaction failed 
        // (but only if it's a NEW file, don't touch existing files)
        if ($newFileUploaded && 
            isset($buktiPath) && 
            $buktiPath && 
            $buktiPath !== $oldBuktiPath && 
            Storage::disk('public')->exists($buktiPath)) {
            
            $cleanupResult = Storage::disk('public')->delete($buktiPath);
            Log::info('Cleaned up orphaned file after update failure', [
                'path' => $buktiPath,
                'cleanup_success' => $cleanupResult
            ]);
        }
        
        Log::error('Error updating payment: ' . $e->getMessage(), [
            'payment_id' => $paymentId,
            'old_amount' => $oldAmount ?? null,
            'new_amount' => $this->editPaymentAmount,
            'has_file' => !is_null($this->editPaymentProof),
            'old_bukti_was_null' => isset($oldBuktiPath) ? is_null($oldBuktiPath) : 'unknown',
            'debug_info' => $this->debugInfo ?? [],
            'trace' => $e->getTraceAsString()
        ]);

        $this->addError('general', 'Gagal mengupdate pembayaran: ' . $e->getMessage());
    }
}

    
    // FIXED: Separate method to reset only form fields
    private function resetPaymentFormFields()
    {
        $this->buktiPembayaran = null;
        $this->keteranganPembayaran = '';
        $this->resetErrorBag(['buktiPembayaran', 'keteranganPembayaran', 'jumlahBayar']);
    }

        public function deleteTransaction()
    {
        if (!$this->transactionToDelete) {
            return;
        }

        try {
            DB::beginTransaction();

            $transaction = TransaksiService::with(['serviceBarangItems', 'serviceJasaItems', 'servicePayments'])
                ->find($this->transactionToDelete);

            if (!$transaction) {
                throw new \Exception('Transaksi tidak ditemukan');
            }

            // Restore stock for barang items (only for non-manual items)
            foreach ($transaction->serviceBarangItems as $item) {
                if (!$item->is_manual && $item->pembelian_id) {
                    $pembelian = Pembelian::find($item->pembelian_id);
                    if ($pembelian) {
                        $pembelian->increment('jumlah_tersisa', $item->jumlah);
                    }
                }
            }

            // Delete payment proof files
            foreach ($transaction->servicePayments as $payment) {
                if ($payment->bukti_bayar && Storage::disk('public')->exists($payment->bukti_bayar)) {
                    Storage::disk('public')->delete($payment->bukti_bayar);
                }
            }

            // Delete related records
            $transaction->serviceBarangItems()->delete();
            $transaction->serviceJasaItems()->delete();
            $transaction->servicePayments()->delete();

            // Delete transaction
            $invoiceNumber = $transaction->invoice;
            $transaction->delete();

            DB::commit();

            $this->dispatch('transaction-deleted', ['invoice' => $invoiceNumber]);
            $this->closeModal();
            session()->flash('message', 'Transaksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting transaction: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function printInvoice($transactionId)
    {
        return redirect()->route('service.invoice', ['id' => $transactionId]);
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

    private function reduceStockFifos($barangId, $jumlahDibutuhkan)
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
    
    public function resetAddItemForm()
    {
        $this->selectedBarangId = '';
        $this->itemJumlah = 1;
        $this->itemHargaJual = 0; // Reset to 0 so user can input freely
        $this->suggestedPrice = 0;
        $this->itemType = 'barang';
        $this->namaJasaBaru = '';
        $this->hargaJasaBaru = 0;
        $this->keteranganJasaBaru = '';
        
        // FIXED: Reset manual item fields
        $this->nama_barang_manual = '';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->harga_beli_manual = 0;
        
        $this->resetErrorBag();
    }

    public function resetEditItemForm()
    {
        $this->editingItem = null;
        $this->editingItemType = '';
        $this->editItemJumlah = 1;
        $this->editItemHargaJual = 0;
        $this->editItemHargaBeli = 0;
        $this->editNamaJasa = '';
        $this->editHargaJasa = 0;
        $this->editKeteranganJasa = '';
        $this->itemToDelete = null;
        $this->itemToDeleteType = '';
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->showEditModal = false;
        $this->showAddItemModal = false;
        $this->showPaymentModal = false;
        $this->showDeleteConfirmModal = false;
        $this->showEditItemModal = false;
        $this->showDeleteItemConfirmModal = false;
        $this->showPaymentDetailModal = false;
        $this->showAddManualItemModal = false;
        $this->selectedTransaction = null;
        $this->selectedTransactionId = null;
        $this->selectedPayment = null;
        $this->transactionToDelete = null;
        $this->resetAddItemForm();
        $this->resetPaymentForm();
        $this->resetEditItemForm();
        $this->resetManualItemForm();
        $this->resetErrorBag();
        $this->showAddItemModal = false;
        $this->selectedTransactionId = null;
        $this->selectedTransaction = null;
        $this->resetItemForm();
    }
    public function getOptimizedSummaryStatsProperty()
    {
        $query = TransaksiService::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pelangganMobil', function ($subQ) {
                        $subQ->where('nama_pelanggan', 'like', '%' . $this->search . '%')
                            ->orWhere('nopol', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status_pekerjaan', $this->statusFilter))
            ->when($this->paymentFilter, fn($q) => $q->where('status_pembayaran', $this->paymentFilter))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo));

        $total = $query->count();

        return [
            'total_transaksi' => $total,
        ];
    }


     /**
     * Reset summary statistics when filters change
     */
    public function updatedDateFrom()
    {
        $this->resetPage();
        // Force recalculation of computed properties
        $this->dispatch('summary-stats-updated');
    }

    public function updatedDateTo()
    {
        $this->resetPage();
        // Force recalculation of computed properties
        $this->dispatch('summary-stats-updated');
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        // Force recalculation of computed properties
        $this->dispatch('summary-stats-updated');
    }

    public function updatedPaymentFilter()
    {
        $this->resetPage();
        // Force recalculation of computed properties
        $this->dispatch('summary-stats-updated');
    }

    public function getTransactionsProperty()
    {
        $query = TransaksiService::with(['pelangganMobil'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('invoice', 'like', '%' . $this->search . '%')
                      ->orWhereHas('pelangganMobil', function($subQ) {
                          $subQ->where('nama_pelanggan', 'like', '%' . $this->search . '%')
                               ->orWhere('nopol', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status_pekerjaan', $this->statusFilter);
            })
            ->when($this->paymentFilter, function($query) {
                $query->where('status_pembayaran', $this->paymentFilter);
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.riwayat-service', [
            'transactions' => $this->getTransactionsProperty()
        ]);
    }

    public function showPaymentDetail($paymentId)
    {
        $this->selectedPayment = ServicePayment::find($paymentId);
        if ($this->selectedPayment) {
            $this->showPaymentDetailModal = true;
        }
    }

    public function showAddManualItem($transactionId)
    {
        $this->selectedTransactionId = $transactionId;
        $this->selectedTransaction = TransaksiService::find($transactionId);
        $this->resetManualItemForm();
        $this->showAddManualItemModal = true;
    }

    public function resetManualItemForm()
    {
        $this->nama_barang_manual = '';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = 0;
        $this->resetErrorBag(['nama_barang_manual', 'jumlah_manual', 'satuan_manual', 'harga_jual_manual']);
    }
     public function resetItemForm()
    {
        $this->selectedBarangId = null;
        $this->itemJumlah = 1;
        $this->itemHargaJual = null;
        $this->nama_barang_manual = '';
        $this->jumlah_manual = 1;
        $this->satuan_manual = 'pcs';
        $this->harga_jual_manual = null;
        $this->harga_beli_manual = null;
        $this->namaJasaBaru = '';
        $this->hargaJasaBaru = null;
        $this->keteranganJasaBaru = '';
        
        $this->selectedBarangItems = [];
        $this->manualItems = [];
        $this->jasaItems = [];
        $this->activeTab = 'barang';
        
        $this->resetErrorBag();
    }
    
    public function addBarangToList()
    {
        $this->validate([
            'selectedBarangId' => 'required|exists:barangs,id',
            'itemJumlah' => 'required|integer|min:1',
            'itemHargaJual' => 'required|numeric|min:0',
        ], [
            'selectedBarangId.required' => 'Pilih barang terlebih dahulu',
            'selectedBarangId.exists' => 'Barang yang dipilih tidak valid',
            'itemJumlah.required' => 'Jumlah harus diisi',
            'itemJumlah.min' => 'Jumlah minimal 1',
            'itemHargaJual.required' => 'Harga jual harus diisi',
            'itemHargaJual.min' => 'Harga jual tidak boleh negatif',
        ]);

        // Check if barang already added
        foreach ($this->selectedBarangItems as $item) {
            if ($item['barang_id'] == $this->selectedBarangId) {
                $this->addError('selectedBarangId', 'Barang sudah ditambahkan ke daftar');
                return;
            }
        }

        // Check stock availability
        $barang = collect($this->availableBarangs)->firstWhere('id', $this->selectedBarangId);
        if (!$barang) {
            $this->addError('selectedBarangId', 'Barang tidak ditemukan');
            return;
        }

        if ($this->itemJumlah > $barang['stok']) {
            $this->addError('itemJumlah', "Stok tidak mencukupi. Tersedia: {$barang['stok']}");
            return;
        }

        // Add to list
        $this->selectedBarangItems[] = [
            'barang_id' => $this->selectedBarangId,
            'nama' => $barang['nama'],
            'merk' => $barang['merk'] ?? '',
            'jumlah' => $this->itemJumlah,
            'harga_jual' => $this->itemHargaJual,
            'subtotal' => $this->itemJumlah * $this->itemHargaJual,
        ];

        // Reset form
        $this->selectedBarangId = null;
        $this->itemJumlah = 1;
        $this->itemHargaJual = null;
        $this->suggestedPrice = 0;

        session()->flash('message', 'Barang ditambahkan ke daftar');
    }

    // Remove barang from list
    public function removeBarangFromList($index)
    {
        if (isset($this->selectedBarangItems[$index])) {
            unset($this->selectedBarangItems[$index]);
            $this->selectedBarangItems = array_values($this->selectedBarangItems);
        }
    }

    // Add manual item to the list
    public function addManualToList()
    {
        // Custom validation for manual items
        $errors = [];
        
        if (empty(trim($this->nama_barang_manual))) {
            $errors['nama_barang_manual'] = 'Nama barang manual harus diisi';
        } elseif (strlen(trim($this->nama_barang_manual)) > 255) {
            $errors['nama_barang_manual'] = 'Nama barang maksimal 255 karakter';
        }
        
        if (!is_numeric($this->jumlah_manual) || $this->jumlah_manual < 1) {
            $errors['jumlah_manual'] = 'Jumlah harus berupa angka dan minimal 1';
        }
        
        if (empty(trim($this->satuan_manual))) {
            $errors['satuan_manual'] = 'Satuan harus dipilih';
        }
        
        if (!is_numeric($this->harga_jual_manual) || $this->harga_jual_manual < 0) {
            $errors['harga_jual_manual'] = 'Harga jual harus berupa angka dan tidak boleh negatif';
        }
        
        if (!is_numeric($this->harga_beli_manual) || $this->harga_beli_manual < 0) {
            $errors['harga_beli_manual'] = 'Harga beli harus berupa angka dan tidak boleh negatif';
        }

        // If there are validation errors, add them and return
        if (!empty($errors)) {
            foreach ($errors as $field => $message) {
                $this->addError($field, $message);
            }
            return;
        }

        try {
            // Add to list
            $this->manualItems[] = [
                'nama_barang' => trim($this->nama_barang_manual),
                'jumlah' => (int)$this->jumlah_manual,
                'satuan' => trim($this->satuan_manual),
                'harga_jual' => (float)$this->harga_jual_manual,
                'harga_beli_manual' => (float)$this->harga_beli_manual,
                'subtotal' => (int)$this->jumlah_manual * (float)$this->harga_jual_manual,
            ];

            // Reset form
            $this->nama_barang_manual = '';
            $this->jumlah_manual = 1;
            $this->satuan_manual = 'pcs';
            $this->harga_jual_manual = 0;
            $this->harga_beli_manual = 0;

            // Clear any previous errors
            $this->resetErrorBag(['nama_barang_manual', 'jumlah_manual', 'satuan_manual', 'harga_jual_manual', 'harga_beli_manual']);

            session()->flash('message', 'Barang manual ditambahkan ke daftar');
            
        } catch (\Exception $e) {
            Log::error('Error adding manual item to list: ' . $e->getMessage());
            $this->addError('general', 'Gagal menambahkan barang manual: ' . $e->getMessage());
        }
    }

    // Remove manual item from list
    public function removeManualFromList($index)
    {
        if (isset($this->manualItems[$index])) {
            unset($this->manualItems[$index]);
            $this->manualItems = array_values($this->manualItems);
        }
    }


    // Add jasa to the list
    public function addJasaToList()
    {
        // FIXED: Use custom validation instead of relying on form validation
        $errors = [];
        
        if (empty(trim($this->namaJasaBaru))) {
            $errors['namaJasaBaru'] = 'Nama jasa harus diisi';
        } elseif (strlen(trim($this->namaJasaBaru)) > 255) {
            $errors['namaJasaBaru'] = 'Nama jasa maksimal 255 karakter';
        }
        
        if (!is_numeric($this->hargaJasaBaru) || $this->hargaJasaBaru < 0) {
            $errors['hargaJasaBaru'] = 'Harga jasa harus berupa angka dan tidak boleh negatif';
        } elseif ($this->hargaJasaBaru == 0) {
            $errors['hargaJasaBaru'] = 'Harga jasa harus lebih dari 0';
        }
        
        if (!empty($this->keteranganJasaBaru) && strlen(trim($this->keteranganJasaBaru)) > 500) {
            $errors['keteranganJasaBaru'] = 'Keterangan maksimal 500 karakter';
        }

        // If there are validation errors, add them and return
        if (!empty($errors)) {
            foreach ($errors as $field => $message) {
                $this->addError($field, $message);
            }
            return;
        }

        // Check if jasa with same name already added
        foreach ($this->jasaItems as $item) {
            if (strtolower(trim($item['nama_jasa'])) === strtolower(trim($this->namaJasaBaru))) {
                $this->addError('namaJasaBaru', 'Jasa dengan nama yang sama sudah ditambahkan');
                return;
            }
        }

        try {
            // Add to list
            $this->jasaItems[] = [
                'nama_jasa' => trim($this->namaJasaBaru),
                'harga_jasa' => (float)$this->hargaJasaBaru,
                'keterangan' => !empty(trim($this->keteranganJasaBaru)) ? trim($this->keteranganJasaBaru) : null,
            ];

            // Reset form
            $this->namaJasaBaru = '';
            $this->hargaJasaBaru = 0;
            $this->keteranganJasaBaru = '';

            // Clear any previous errors
            $this->resetErrorBag(['namaJasaBaru', 'hargaJasaBaru', 'keteranganJasaBaru']);

            Log::info('Jasa added to list', [
                'nama_jasa' => trim($this->namaJasaBaru),
                'harga_jasa' => (float)$this->hargaJasaBaru,
                'total_jasa_items' => count($this->jasaItems)
            ]);

            session()->flash('message', 'Jasa berhasil ditambahkan ke daftar');
            
        } catch (\Exception $e) {
            Log::error('Error adding jasa to list: ' . $e->getMessage());
            $this->addError('general', 'Gagal menambahkan jasa: ' . $e->getMessage());
        }
    }

    // Remove jasa from list
    public function removeJasaFromList($index)
    {
        if (isset($this->jasaItems[$index])) {
            unset($this->jasaItems[$index]);
            $this->jasaItems = array_values($this->jasaItems);
        }
    }

    // Save all items to transaction
    public function saveAllItemsToTransaction()
    {
        // Validate transaction exists
        if (!$this->selectedTransactionId) {
            $this->addError('general', 'Transaction ID is required');
            return;
        }

        $transaction = TransaksiService::find($this->selectedTransactionId);
        if (!$transaction) {
            $this->addError('general', 'Transaksi tidak ditemukan');
            return;
        }

        // Check if there are items to save
        if (empty($this->selectedBarangItems) && empty($this->manualItems) && empty($this->jasaItems)) {
            $this->addError('general', 'Tidak ada item yang akan disimpan');
            return;
        }

        try {
            DB::beginTransaction();

            $totalItemsAdded = 0;
            $totalAmount = 0;

            // Process barang items
            foreach ($this->selectedBarangItems as $item) {
                // Double check stock availability
                $availableStock = Pembelian::where('barang_id', $item['barang_id'])
                    ->where('jumlah_tersisa', '>', 0)
                    ->sum('jumlah_tersisa');

                if ($item['jumlah'] > $availableStock) {
                    throw new \Exception("Stok barang {$item['nama']} tidak mencukupi. Tersedia: {$availableStock}");
                }

                // Reduce stock using FIFO
                $usedPembelians = $this->reduceStockFifo($item['barang_id'], $item['jumlah']);

                // Create service barang items
                foreach ($usedPembelians as $used) {
                    ServiceBarangItem::create([
                        'transaksi_service_id' => $this->selectedTransactionId,
                        'pembelian_id' => $used['pembelian_id'],
                        'barang_id' => $item['barang_id'],
                        'jumlah' => $used['jumlah'],
                        'harga_jual' => $item['harga_jual'],
                        'subtotal' => $used['jumlah'] * $item['harga_jual'],
                        'is_manual' => false,
                        'nama_barang_manual' => null,
                        'satuan' => null,
                    ]);
                }

                $totalItemsAdded++;
                $totalAmount += $item['subtotal'];
            }

            // Process manual items
            foreach ($this->manualItems as $item) {
                ServiceBarangItem::create([
                    'transaksi_service_id' => $this->selectedTransactionId,
                    'barang_id' => null,
                    'pembelian_id' => null,
                    'nama_barang_manual' => $item['nama_barang'],
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'harga_jual' => $item['harga_jual'],
                    'harga_beli_manual' => $item['harga_beli_manual'],
                    'subtotal' => $item['subtotal'],
                    'is_manual' => true,
                ]);

                $totalItemsAdded++;
                $totalAmount += $item['subtotal'];
            }

            // FIXED: Process jasa items properly
            foreach ($this->jasaItems as $item) {
                $jasaItem = ServiceJasaItem::create([
                    'transaksi_service_id' => $this->selectedTransactionId,
                    'nama_jasa' => $item['nama_jasa'],
                    'harga_jasa' => $item['harga_jasa'],
                    'subtotal' => $item['harga_jasa'], // For jasa, subtotal = harga_jasa
                    'keterangan' => $item['keterangan']
                ]);

                Log::info('Jasa item saved to database', [
                    'jasa_item_id' => $jasaItem->id,
                    'transaksi_service_id' => $this->selectedTransactionId,
                    'nama_jasa' => $item['nama_jasa'],
                    'harga_jasa' => $item['harga_jasa'],
                    'keterangan' => $item['keterangan']
                ]);

                $totalItemsAdded++;
                $totalAmount += $item['harga_jasa'];
            }

            // Recalculate transaction totals
            $this->recalculateTransactionTotals($transaction);

            DB::commit();

            Log::info('All items saved successfully', [
                'transaction_id' => $this->selectedTransactionId,
                'total_items_added' => $totalItemsAdded,
                'total_amount' => $totalAmount,
                'barang_items' => count($this->selectedBarangItems),
                'manual_items' => count($this->manualItems),
                'jasa_items' => count($this->jasaItems)
            ]);

            $this->closeModal();
            $this->loadAvailableBarangs(); // Refresh stock data
            
            $this->dispatch('items-added', [
                'invoice' => $transaction->invoice,
                'total_items' => $totalItemsAdded,
                'total_amount' => $totalAmount
            ]);

            session()->flash('message', "Berhasil menambahkan {$totalItemsAdded} item dengan total Rp" . number_format($totalAmount, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding multiple items to transaction: ' . $e->getMessage(), [
                'transaction_id' => $this->selectedTransactionId,
                'barang_items' => count($this->selectedBarangItems),
                'manual_items' => count($this->manualItems),
                'jasa_items' => count($this->jasaItems),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('general', 'Gagal menambah item: ' . $e->getMessage());
        }
    }
}
