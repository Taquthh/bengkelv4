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

        public function closeDiscountModal()
    {
        $this->showDiscountModal = false;
        $this->editingDiscount = false;
        $this->editDiskon = 0;
        $this->editTipeDiskon = 'nominal';
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

        $this->resetItemForm();
        $this->resetPaymentDetailState();
        $this->resetEditItemForm();
        $this->resetErrorBag();
    }
    
    public function closeDCModal()
    {
        $this->showDetailModal = true;
        $this->showDeleteConfirmModal = false;
        $this->showDeleteItemConfirmModal = false;

        $this->resetItemForm();
        $this->resetPaymentDetailState();
        $this->resetEditItemForm();
        $this->resetErrorBag();
    }