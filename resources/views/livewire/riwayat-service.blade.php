<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 mt-16">
    <div >
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-gradient-to-r from-emerald-50 via-green-50 to-teal-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-3xl mb-5 flex items-center shadow-lg">
                <div class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-green-500 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="font-semibold text-base">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-gradient-to-r from-red-50 via-pink-50 to-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-3xl mb-5 flex items-center shadow-lg">
                <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-base">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            Riwayat Transaksi Penjualan
                        </h1>
                        <p class="text-blue-100 mt-1">Kelola dan monitor seluruh transaksi penjualan</p>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            {{-- Enhanced Summary Cards with Additional Stats --}}
            <div class="p-6">
                {{-- Main Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    {{-- Total Transaksi Card --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200/50 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-600 text-sm font-medium">Total Transaksi</p>
                                <p class="text-2xl font-bold text-blue-900 mt-1">{{ number_format($this->optimizedSummaryStats['total_transaksi']) }}</p>
                                <p class="text-xs text-blue-500 mt-1">
                                    @if($this->dateFrom && $this->dateTo)
                                        {{ Carbon\Carbon::parse($this->dateFrom)->format('d/m') }} - {{ Carbon\Carbon::parse($this->dateTo)->format('d/m/Y') }}
                                    @else
                                        Semua periode
                                    @endif
                                </p>
                            </div>
                            <div class="p-3 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                
                {{-- Advanced Filters --}}
                <div class="grid grid-cols-12 gap-4">
                <!-- Search -->
                <div class="col-span-12 sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Transaksi</label>
                    <div class="relative">
                        <input wire:model.live="search" type="text" class="w-full px-4 py-3 pl-12 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Invoice, nama, nopol...">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Status Pekerjaan Filter -->
                <div class="col-span-12 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pekerjaan</label>
                    <select wire:model.live="statusFilter" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="belum_dikerjakan">Belum Dikerjakan</option>
                        <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <!-- Status Pembayaran Filter -->
                <div class="col-span-12 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                    <select wire:model.live="paymentFilter" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="belum">Belum Bayar</option>
                        <option value="sebagian">Sebagian</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="col-span-12 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input wire:model.live="dateFrom" type="date" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Date To -->
                <div class="col-span-12 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input wire:model.live="dateTo" type="date" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Reset Filter -->
                <div class="col-span-12 sm:col-span-1 flex items-end">
                    <button wire:click="resetFilters"
                             class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                        Reset
                    </button>
                </div>
            </div>
            </div>
        </div>
    </div>

        <!-- Transactions Table -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 cursor-pointer" wire:click="sortBy('invoice')">
                                Invoice
                                @if($sortBy === 'invoice')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Kendaraan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 cursor-pointer" wire:click="sortBy('total_keseluruhan')">
                                Total
                                @if($sortBy === 'total_keseluruhan')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status Pekerjaan</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status Pembayaran</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 cursor-pointer" wire:click="sortBy('created_at')">
                                Tanggal
                                @if($sortBy === 'tanggal_service')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-blue-600">{{ $transaction->invoice }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $transaction->pelangganMobil->nama_pelanggan }}</div>
                                    @if($transaction->pelangganMobil->kontak)
                                        <div class="text-sm text-gray-500">📞 {{ $transaction->pelangganMobil->kontak }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $transaction->pelangganMobil->nopol }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->pelangganMobil->merk_mobil }} {{ $transaction->pelangganMobil->tipe_mobil }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-green-600">Rp{{ number_format($transaction->total_keseluruhan, 0, ',', '.') }}</div>
                                    @if($transaction->sisa_pembayaran > 0)
                                        <div class="text-sm text-orange-600">Sisa: Rp{{ number_format($transaction->sisa_pembayaran, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($transaction->status_pekerjaan === 'belum_dikerjakan')
                                        <span class="px-3 py-1 text-xs font-bold bg-orange-100 text-orange-800 rounded-full">BELUM DIKERJAKAN</span>
                                    @elseif($transaction->status_pekerjaan === 'sedang_dikerjakan')
                                        <span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full">SEDANG DIKERJAKAN</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">SELESAI</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $sisa = $transaction->sisa_pembayaran ?? 0;
                                    @endphp

                                    @if($sisa == 0)
                                        <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">LUNAS</span>
                                    @elseif($transaction->status_pembayaran === 'sebagian')
                                        <span class="px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-full">SEBAGIAN</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-full">BELUM BAYAR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $transaction->tanggal_service->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2 flex-wrap gap-1">
                                        <!-- Detail Button -->
                                        <button wire:click="showDetail({{ $transaction->id }})"
                                                 class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors"
                                                 title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>

                                        <!-- Edit Button -->
                                        <button wire:click="showEdit({{ $transaction->id }})"
                                                 class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors"
                                                 title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <!-- Add Item Button -->
                                        <button wire:click="showAddItem({{ $transaction->id }})"
                                                 class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors"
                                                 title="Tambah Item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>

                                        <!-- Payment Button -->
                                        <button wire:click="showPayment({{ $transaction->id }})"
                                                 class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-colors"
                                                 title="Bayar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </button>

                                        <!-- Print Invoice Button -->
                                        <button wire:click="printInvoice({{ $transaction->id }})"
                                                 class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                                                 title="Cetak Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button wire:click="confirmDelete({{ $transaction->id }})"
                                                 class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors"
                                                 title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">Tidak ada transaksi ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau buat transaksi baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    @include('livewire.partials.modal-detail')
    <!-- Added Discount Edit Modal -->
    @include('livewire.partials.modal-discount')
    <!-- Edit Modal -->
    @include('livewire.partials.modal-edit')
    <!-- Add Multiple Items Modal -->
    @include('livewire.partials.modal-add-item')
    <!-- Edit Item Modal -->
    @include('livewire.partials.modal-edit-item')
    <!-- Delete Item Confirmation Modal -->
    @include('livewire.partials.modal-delete-item')
    <!-- Payment Detail Modal -->
    @include('livewire.partials.modal-payment-detail')
    <!-- Payment Modal -->
    @include('livewire.partials.modal-payment')
    <!-- Delete Confirmation Modal -->
    @include('livewire.partials.modal-delete-confirm')

    <!-- Enhanced Custom Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #f8fafc, #e2e8f0);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #cbd5e0, #a0aec0);
            border-radius: 10px;
            border: 2px solid #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #a0aec0, #718096);
        }
        .backdrop-blur-xl {
            backdrop-filter: blur(20px);
        }
        /* Enhanced focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        /* Smooth transitions for all interactive elements */
        * {
            transition-property: transform, box-shadow, background-color, border-color, color, opacity;
            transition-duration: 0.2s;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Table hover effect */
        tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateY(-1px);
        }
        /* Button hover effects */
        button:hover {
            transform: translateY(-1px);
        }
        /* Modal animation */
        .fixed {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        /* Loading state for buttons */
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
        /* Responsive design improvements */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                font-size: 14px;
            }
            
            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .py-4 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }
        /* Status badge animations */
        .status-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        /* Print optimizations */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .shadow-xl, .shadow-2xl {
                box-shadow: none !important;
            }
            
            .rounded-3xl, .rounded-2xl, .rounded-xl {
                border-radius: 8px !important;
            }
        }
    </style>
</div>

<!-- Enhanced JavaScript -->
<script>
// Livewire listeners
document.addEventListener('livewire:init', () => {
    Livewire.on('transaction-updated', (data) => {
        showToastMessage('Berhasil!', `Transaksi ${data[0].invoice} berhasil diupdate`, 'success');
    });

    Livewire.on('items-added', (data) => {
        showToastMessage('Item Ditambahkan!', `${data[0].type} senilai Rp${formatNumber(data[0].amount)} berhasil ditambahkan ke ${data[0].invoice}`, 'success');
    });

    Livewire.on('manual-item-added', (data) => {
        showToastMessage('Barang Manual Ditambahkan!', `${data[0].item_name} senilai Rp${formatNumber(data[0].amount)} berhasil ditambahkan ke ${data[0].invoice}`, 'success');
    });

    Livewire.on('item-updated', (data) => {
        showToastMessage('Item Diupdate!', `Item ${data[0].type} berhasil diupdate di ${data[0].invoice}`, 'success');
    });

    Livewire.on('item-deleted', (data) => {
        showToastMessage('Item Dihapus!', `Item ${data[0].type} berhasil dihapus dari ${data[0].invoice}`, 'success');
    });

    Livewire.on('payment-added', (data) => {
        let statusText = data[0].status === 'lunas' ? 'LUNAS' : 'SEBAGIAN';
        showToastMessage('Pembayaran Berhasil!', `Pembayaran Rp${formatNumber(data[0].amount)} untuk ${data[0].invoice} berhasil. Status: ${statusText}`, 'success');
    });

    Livewire.on('payment-updated', (data) => {
        showToastMessage('Pembayaran Diupdate!', `Pembayaran berhasil diupdate dengan jumlah baru Rp${formatNumber(data[0].new_amount)}`, 'success');
    });

    Livewire.on('transaction-deleted', (data) => {
        showToastMessage('Terhapus!', `Transaksi ${data[0].invoice} berhasil dihapus`, 'success');
    });

    Livewire.on('open-payment-proof', (data) => {
        window.open(data[0].url, '_blank');
    });
});

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function showToastMessage(title, message, type = 'success') {
    const colors = {
        success: 'from-emerald-500 via-green-500 to-teal-500',
        error: 'from-red-500 via-pink-500 to-red-500',
        warning: 'from-yellow-500 via-orange-500 to-red-500',
        info: 'from-blue-500 via-indigo-500 to-purple-500'
    };

    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>',
        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>',
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>',
        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
    };

    const bgColor = colors[type] || colors.success;
    const icon = icons[type] || icons.success;

    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(t => t.remove());

    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-6 right-6 bg-gradient-to-r ${bgColor} text-white px-8 py-4 rounded-3xl shadow-2xl z-50 flex items-center space-x-4 transform translate-x-full border border-white/20 backdrop-blur-lg max-w-md`;
    toast.innerHTML = `
        <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
        </div>
        <div class="min-w-0 flex-1">
            <div class="font-bold text-lg truncate">${title}</div>
            <div class="text-sm opacity-90 break-words">${message}</div>
        </div>
    `;

    document.body.appendChild(toast);

    // Slide in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.transition = 'transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
    }, 100);

    // Auto hide
    const duration = type === 'error' ? 5000 : 3500;
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'transform 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Close modal with Escape
    if (e.key === 'Escape') {
        @this.call('closeModal');
    }

    // Ctrl + N for new transaction
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        window.location.href = "{{ route('transaksi.services') }}";
    }

    // Ctrl + F to focus search
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.querySelector('input[wire\\:model\\.live="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// Export utility functions for external use
window.MultipleItemsUtils = {
    saveDraft: function() {
        // Manual draft save trigger
        if (typeof saveDraft === 'function') saveDraft();
    },
    
    focusFirstInput: function() {
        // Manual focus trigger
        if (typeof focusFirstInput === 'function') focusFirstInput();
    },
    
    formatCurrency: function(amount) {
        return 'Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}

console.log('🚗 Riwayat Transaksi Service loaded successfully!');
console.log('🔧 Features: View, Edit, Add Items, Edit Items, Delete Items, Add Payments, Edit Payments, Manual Items, Print Invoice, Delete');
console.log('⌨️ Keyboard shortcuts: Escape (close modal), Ctrl+N (new transaction), Ctrl+F (focus search)');
console.log('🔄 Auto-refresh: Every 30 seconds when page is visible');
console.log('✏️ UPDATED: Edit and delete buttons now ALWAYS VISIBLE for better user experience');
console.log('💰 NEW: Payment detail/edit modal with photo proof support');
console.log('🔧 NEW: Manual item support for indent/order items');
</script>