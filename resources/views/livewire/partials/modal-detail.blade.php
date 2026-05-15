<div>
 @if($showDetailModal && $selectedTransaction)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-900">Detail Transaksi</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Transaction Info -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-blue-50 rounded-2xl p-4">
                            <h4 class="text-lg font-semibold text-blue-900 mb-3">Informasi Transaksi</h4>
                            <div class="space-y-2 text-sm">
                                <div><strong>Invoice:</strong> {{ $selectedTransaction->invoice }}</div>
                                <div><strong>Tanggal:</strong> {{ $selectedTransaction->created_at->format('d F Y, H:i') }}</div>
                                <div><strong>Kasir:</strong> {{ $selectedTransaction->kasir }}</div>
                                <div><strong>Strategi Bayar:</strong> {{ strtoupper(str_replace('_', ' ', $selectedTransaction->strategi_pembayaran)) }}</div>
                            </div>
                        </div>
                        <div class="bg-purple-50 rounded-2xl p-4">
                            <h4 class="text-lg font-semibold text-purple-900 mb-3">Informasi Pelanggan</h4>
                            <div class="space-y-2 text-sm">
                                <div><strong>Nama:</strong> {{ $selectedTransaction->pelangganMobil->nama_pelanggan }}</div>
                                @if($selectedTransaction->pelangganMobil->kontak)
                                    <div><strong>Kontak:</strong> {{ $selectedTransaction->pelangganMobil->kontak }}</div>
                                @endif
                                <div><strong>Nopol:</strong> {{ $selectedTransaction->pelangganMobil->nopol }}</div>
                                <div><strong>Kendaraan:</strong> {{ $selectedTransaction->pelangganMobil->merk_mobil }} {{ $selectedTransaction->pelangganMobil->tipe_mobil }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="text-center p-4 rounded-2xl {{ $selectedTransaction->status_pekerjaan === 'selesai' ? 'bg-green-100' : ($selectedTransaction->status_pekerjaan === 'sedang_dikerjakan' ? 'bg-blue-100' : 'bg-orange-100') }}">
                            <div class="text-sm font-medium text-gray-600">Status Pekerjaan</div>
                            <div class="text-xl font-bold {{ $selectedTransaction->status_pekerjaan === 'selesai' ? 'text-green-800' : ($selectedTransaction->status_pekerjaan === 'sedang_dikerjakan' ? 'text-blue-800' : 'text-orange-800') }}">
                                {{ strtoupper(str_replace('_', ' ', $selectedTransaction->status_pekerjaan)) }}
                            </div>
                        </div>
                        <div class="text-center p-4 rounded-2xl {{ $selectedTransaction->status_pembayaran === 'lunas' ? 'bg-green-100' : ($selectedTransaction->status_pembayaran === 'sebagian' ? 'bg-yellow-100' : 'bg-red-100') }}">
                            <div class="text-sm font-medium text-gray-600">Status Pembayaran</div>
                                @php
                                    $status = $selectedTransaction->status_pembayaran;
                                @endphp

                                <div class="text-xl font-bold 
                                    {{ $status === 'lunas' ? 'text-green-800' : ($status === 'sebagian' ? 'text-yellow-800' : ($status === 'belum' ? 'text-red-800' : 'text-gray-500')) }}">
                                    
                                    @if ($status === 'belum')
                                        BELUM BAYAR
                                    @elseif ($status === 'sebagian')
                                        SEBAGIAN
                                    @elseif ($status === 'lunas')
                                        LUNAS
                                    @else
                                        STATUS TIDAK DIKENAL ({{ $status }})
                                    @endif
                                </div>
                        </div>
                    </div>

                    <!-- Keluhan & Diagnosis -->
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Keluhan</h4>
                            <p class="text-gray-700">{{ $selectedTransaction->keluhan }}</p>
                        </div>
                        @if($selectedTransaction->diagnosa)
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Diagnosa</h4>
                                <p class="text-gray-700">{{ $selectedTransaction->diagnosa }}</p>
                            </div>
                        @endif
                        @if($selectedTransaction->pekerjaan_dilakukan)
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Pekerjaan Dilakukan</h4>
                                <p class="text-gray-700">{{ $selectedTransaction->pekerjaan_dilakukan }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Items with ALWAYS VISIBLE Edit/Delete Buttons -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Barang Items -->
                        @if($selectedTransaction->serviceBarangItems->count() > 0)
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Items Barang
                                </h4>
                                <div class="text-sm text-gray-500 bg-blue-50 px-3 py-1 rounded-full">
                                    {{ $selectedTransaction->serviceBarangItems->count() }} item
                                </div>
                            </div>
                            <div class="space-y-3">
                                @foreach($selectedTransaction->serviceBarangItems as $index => $item)
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 shadow-sm">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <div class="font-semibold text-blue-900 text-base mb-1">
                                                    @if($item->is_manual)
                                                        {{ $item->nama_barang_manual ?? '-' }}
                                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full ml-2 font-bold">MANUAL</span>
                                                    @else
                                                        {{ $item->barang->nama ?? 'Barang tidak ditemukan' }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-blue-700 mb-2">
                                                    <span class="font-medium">Qty:</span> {{ $item->jumlah }} × 
                                                    <span class="font-medium">Harga:</span> Rp{{ number_format($item->harga_jual, 0, ',', '.') }}
                                                </div>
                                                <div class="text-base font-bold text-green-600">
                                                    Subtotal: Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- ALWAYS VISIBLE Action Buttons -->
                                        <div class="flex justify-end space-x-2 pt-3 border-t border-blue-200">
                                            <button wire:click="editBarangItem({{ $item->id }})"
                                                    class="flex items-center space-x-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-sm font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                            <button wire:click="confirmDeleteItem({{ $item->id }}, 'barang')"
                                                    class="flex items-center space-x-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                        <!-- Jasa Items -->
                        @if($selectedTransaction->serviceJasaItems->count() > 0)
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Items Jasa
                                    </h4>
                                    <div class="text-sm text-gray-500 bg-purple-50 px-3 py-1 rounded-full">
                                        {{ $selectedTransaction->serviceJasaItems->count() }} item
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    @foreach($selectedTransaction->serviceJasaItems as $item)
                                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-4 shadow-sm">
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex-1">
                                                    <div class="font-semibold text-purple-900 text-base mb-1">{{ $item->nama_jasa }}</div>
                                                    <div class="text-base font-bold text-green-600 mb-2">
                                                        Harga: Rp{{ number_format($item->harga_jasa, 0, ',', '.') }}
                                                    </div>
                                                    @if($item->keterangan)
                                                        <div class="text-sm text-purple-700 bg-purple-100 rounded-lg p-2">
                                                            <span class="font-medium">Keterangan:</span> {{ $item->keterangan }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- ALWAYS VISIBLE Action Buttons -->
                                            <div class="flex justify-end space-x-2 pt-3 border-t border-purple-200">
                                                <button wire:click="editJasaItem({{ $item->id }})"
                                                        class="flex items-center space-x-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </button>
                                                <button wire:click="confirmDeleteItem({{ $item->id }}, 'jasa')"
                                                        class="flex items-center space-x-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment History -->
                    @if($selectedTransaction->servicePayments->count() > 0)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Riwayat Pembayaran
                            </h4>
                            <div class="space-y-3">
                                @foreach($selectedTransaction->servicePayments as $payment)
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="font-semibold text-green-900 text-lg">Rp{{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</div>
                                                <div class="text-sm text-green-700 mb-2">
                                                    📅 {{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d/m/Y') }} • 
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">{{ strtoupper($payment->metode_pembayaran) }}</span>
                                                </div>
                                                @if($payment->keterangan)
                                                    <div class="text-sm text-green-600 bg-green-100 rounded-lg p-2 mb-2">
                                                        <span class="font-medium">Keterangan:</span> {{ $payment->keterangan }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-green-600">Kasir: {{ $payment->kasir }}</div>
                                            </div>
                                            
                                            <!-- Payment Action Buttons -->
                                            <div class="flex flex-col space-y-2 ml-4">
                                                @if($payment->bukti_bayar)
                                                    <button wire:click="viewPaymentProof({{ $payment->id }})" 
                                                            class="flex items-center space-x-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        <span>Lihat Bukti</span>
                                                    </button>
                                                @endif
                                                <button wire:click="openPaymentDetail({{ $payment->id }})" 
                                                        class="flex items-center space-x-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Detail</span>
                                                </button>

                                                <button wire:click="editPayment({{ $payment->id }})" 
                                                        class="flex items-center space-x-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Total Summary -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">Ringkasan Keuangan</h4>
                            <!-- Added edit discount button -->
                            <button wire:click="showEditDiscount({{ $selectedTransaction->id }})"
                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit Diskon</span>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-center">
                            <div>
                                <div class="text-2xl font-bold text-green-600">Rp{{ number_format($selectedTransaction->total_barang, 0, ',', '.') }}</div>
                                <div class="text-sm text-green-700">Total Barang</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-600">Rp{{ number_format($selectedTransaction->total_jasa, 0, ',', '.') }}</div>
                                <div class="text-sm text-purple-700">Total Jasa</div>
                            </div>
                            <!-- Added discount information -->
                            <div>
                                @php
                                    $subtotal = ($selectedTransaction->subtotal_sebelum_diskon ?? ($selectedTransaction->total_barang + $selectedTransaction->total_jasa));
                                    $diskonAmount = $selectedTransaction->diskon_amount ?? 0;
                                @endphp
                                <div class="text-lg font-bold text-gray-600">Rp{{ number_format($subtotal, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-700">Subtotal</div>
                                @if($selectedTransaction && $selectedTransaction->diskon > 0)
                                    <div class="text-sm text-red-600 mt-1">
                                        <span>Diskon: </span>
                                        <strong>
                                            @if($selectedTransaction->tipe_diskon === 'persentase')
                                                {{ $selectedTransaction->diskon }}%
                                            @else
                                                Rp{{ number_format($selectedTransaction->diskon, 0, ',', '.') }}
                                            @endif
                                        </strong>
                                        
                                        @if(isset($diskonAmount) && $diskonAmount > 0)
                                            <div class="font-medium">
                                                (-Rp{{ number_format($diskonAmount, 0, ',', '.') }})
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-gray-900">Rp{{ number_format($selectedTransaction->total_keseluruhan, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-700">Total Akhir</div>
                                @if($selectedTransaction->sisa_pembayaran > 0)
                                    <div class="text-sm text-orange-600 mt-1">Sisa: Rp{{ number_format($selectedTransaction->sisa_pembayaran, 0, ',', '.') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Help Text for Users -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <div class="font-semibold mb-1">💡 Cara Edit Item:</div>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Klik tombol <span class="bg-yellow-100 px-2 py-1 rounded text-xs font-bold">Edit</span> pada item yang ingin diubah</li>
                                    <li>Klik tombol <span class="bg-red-100 px-2 py-1 rounded text-xs font-bold">Hapus</span> untuk menghapus item</li>
                                    <li>Total transaksi akan otomatis diperbarui setelah edit</li>
                                    <li>Stok barang akan disesuaikan secara otomatis</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif     
</div>
   