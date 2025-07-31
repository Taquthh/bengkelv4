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
                                @if($sortBy === 'created_at')
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
                                    <div class="text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y') }}</div>
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
                                                
                                                <button wire:click="editPayment({{ $payment->id }})" 
                                                        class="flex items-center space-x-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </button>
                                                
                                                <button wire:click="openPaymentDetail({{ $payment->id }})" 
                                                        class="flex items-center space-x-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Detail</span>
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
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-center">
                            <div>
                                <div class="text-2xl font-bold text-green-600">Rp{{ number_format($selectedTransaction->total_barang, 0, ',', '.') }}</div>
                                <div class="text-sm text-green-700">Total Barang</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-600">Rp{{ number_format($selectedTransaction->total_jasa, 0, ',', '.') }}</div>
                                <div class="text-sm text-purple-700">Total Jasa</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-gray-900">Rp{{ number_format($selectedTransaction->total_keseluruhan, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-700">Total Keseluruhan</div>
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

    <!-- Edit Modal -->
    @if($showEditModal && $selectedTransaction)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-900">Edit Transaksi - {{ $selectedTransaction->invoice }}</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Pekerjaan</label>
                            <select wire:model="editStatusPekerjaan" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="belum_dikerjakan">Belum Dikerjakan</option>
                                <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>

                    <!-- Description Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosa</label>
                        <textarea wire:model="editDiagnosa" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Hasil diagnosa..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Dilakukan</label>
                        <textarea wire:model="editPekerjaanDilakukan" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Detail pekerjaan yang sudah dilakukan..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Pembayaran</label>
                        <textarea wire:model="editKeterangan" rows="2" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Keterangan tambahan..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                            Batal
                        </button>
                        <button wire:click="updateTransaction" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 font-semibold transition-all duration-300">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

<!-- Add Item Modal -->
@if($showAddItemModal && $selectedTransaction)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-gray-900">Tambah Item - {{ $selectedTransaction->invoice }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-6">
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Item Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Item</label>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="cursor-pointer {{ $itemType === 'barang' ? 'ring-2 ring-blue-500' : '' }}" wire:click="$set('itemType', 'barang')">
                            <div class="p-4 border-2 rounded-xl {{ $itemType === 'barang' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Barang</div>
                                        <div class="text-sm text-gray-500">Spare part dari stok</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cursor-pointer {{ $itemType === 'manual' ? 'ring-2 ring-orange-500' : '' }}" wire:click="$set('itemType', 'manual')">
                            <div class="p-4 border-2 rounded-xl {{ $itemType === 'manual' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }} transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Manual</div>
                                        <div class="text-sm text-gray-500">Barang indent/order</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cursor-pointer {{ $itemType === 'jasa' ? 'ring-2 ring-purple-500' : '' }}" wire:click="$set('itemType', 'jasa')">
                            <div class="p-4 border-2 rounded-xl {{ $itemType === 'jasa' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }} transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Jasa</div>
                                        <div class="text-sm text-gray-500">Layanan atau service</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($itemType === 'barang')
                    <!-- Barang Form -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                            <select wire:model="selectedBarangId" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($availableBarangs as $barang)
                                    <option value="{{ $barang['id'] }}">
                                        {{ $barang['nama'] }} 
                                        @if($barang['merk']) - {{ $barang['merk'] }} @endif
                                        (Stok: {{ $barang['stok'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                <input wire:model="itemJumlah" type="number" min="1" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="1">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Jual
                                    @if($suggestedPrice > 0)
                                        <span class="text-xs text-blue-600 font-normal">(Saran: Rp{{ number_format($suggestedPrice, 0, ',', '.') }})</span>
                                    @endif
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input wire:model="itemHargaJual" 
                                           type="number" 
                                           min="0" 
                                           step="1000"
                                           class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="0">
                                </div>
                                @if($suggestedPrice > 0 && $itemHargaJual != $suggestedPrice)
                                    <button type="button" 
                                            wire:click="$set('itemHargaJual', {{ $suggestedPrice }})"
                                            class="mt-2 text-xs text-blue-600 hover:text-blue-800 underline">
                                        Gunakan harga saran (Rp{{ number_format($suggestedPrice, 0, ',', '.') }})
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        @if($selectedBarangId && $itemJumlah > 0 && $itemHargaJual > 0)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="text-sm text-blue-800">
                                    <strong>Subtotal:</strong> {{ $itemJumlah }} × Rp{{ number_format($itemHargaJual, 0, ',', '.') }} = 
                                    <span class="font-bold text-lg">Rp{{ number_format($itemJumlah * $itemHargaJual, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                @elseif($itemType === 'manual')
                    <!-- Manual Item Form -->
                    <div class="space-y-4">
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-orange-800">Barang Manual/Indent</h4>
                                    <p class="text-sm text-orange-700">Barang yang perlu diorder atau tidak ada di stok inventory</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang *</label>
                                <input wire:model="nama_barang_manual" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: Filter Oli Toyota Avanza">
                                @error('nama_barang_manual') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>  
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                                <input wire:model="jumlah_manual" type="number" min="1" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="1">
                                @error('jumlah_manual') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Satuan *</label>
                                <select wire:model="satuan_manual" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="pcs">pcs</option>
                                    <option value="set">set</option>
                                    <option value="unit">unit</option>
                                    <option value="meter">meter</option>
                                    <option value="liter">liter</option>
                                    <option value="kg">kg</option>
                                    <option value="dus">dus</option>
                                    <option value="box">box</option>
                                </select>
                                @error('satuan_manual') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input wire:model="harga_jual_manual" type="number" min="0" step="1000" class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="0">
                                </div>
                                @error('harga_jual_manual') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        @if($jumlah_manual > 0 && $harga_jual_manual > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                                <div class="text-sm text-orange-800">
                                    <strong>Subtotal:</strong> {{ $jumlah_manual }} {{ $satuan_manual }} × Rp{{ number_format($harga_jual_manual, 0, ',', '.') }} = 
                                    <span class="font-bold text-lg">Rp{{ number_format($jumlah_manual * $harga_jual_manual, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                @else
                    <!-- Jasa Form -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jasa</label>
                            <input wire:model="namaJasaBaru" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Contoh: Ganti oli, Service AC, dll">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jasa</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input wire:model="hargaJasaBaru" 
                                       type="number" 
                                       min="0" 
                                       step="1000"
                                       class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                       placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                            <textarea wire:model="keteranganJasaBaru" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Detail pekerjaan yang dikerjakan..."></textarea>
                        </div>
                        
                        @if($hargaJasaBaru > 0)
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                                <div class="text-sm text-purple-800">
                                    <strong>Harga Jasa:</strong> 
                                    <span class="font-bold text-lg">Rp{{ number_format($hargaJasaBaru, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                        Batal
                    </button>
                    <button 
    wire:click="addItemToTransaction"
    class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 font-semibold transition-all duration-300"
>
    <span class="flex items-center justify-center space-x-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span>Tambah Item</span>
    </span>
</button>

                </div>
            </div>
        </div>
    </div>
@endif

    <!-- Edit Item Modal -->
    @if($showEditItemModal && $editingItem)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Edit {{ $editingItemType === 'barang' ? 'Barang' : 'Jasa' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($editingItemType === 'barang')
                        <!-- Edit Barang Form -->
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-xl p-4">
                                <h4 class="font-semibold text-blue-900 mb-2">Item yang sedang diedit:</h4>
                                <div class="text-sm text-blue-700">
                                    @if($editingItem->is_manual)
                                        {{ $editingItem->nama_barang_manual ?? '-' }}
                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full ml-2">MANUAL</span>
                                    @else
                                        {{ $editingItem->barang->nama ?? 'Barang tidak ditemukan' }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                    <input wire:model="editItemJumlah" type="number" min="1" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="1">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                        <input wire:model="editItemHargaJual" 
                                               type="number" 
                                               min="0" 
                                               step="1000"
                                               class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            
                            @if($editItemJumlah > 0 && $editItemHargaJual > 0)
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <div class="text-sm text-blue-800">
                                        <strong>Subtotal Baru:</strong> {{ $editItemJumlah }} × Rp{{ number_format($editItemHargaJual, 0, ',', '.') }} = 
                                        <span class="font-bold text-lg">Rp{{ number_format($editItemJumlah * $editItemHargaJual, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Edit Jasa Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jasa</label>
                                <input wire:model="editNamaJasa" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Nama jasa">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jasa</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input wire:model="editHargaJasa" 
                                           type="number" 
                                           min="0" 
                                           step="1000"
                                           class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                           placeholder="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                                <textarea wire:model="editKeteranganJasa" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Detail pekerjaan yang dikerjakan..."></textarea>
                            </div>
                            
                            @if($editHargaJasa > 0)
                                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                                    <div class="text-sm text-purple-800">
                                        <strong>Harga Jasa Baru:</strong> 
                                        <span class="font-bold text-lg">Rp{{ number_format($editHargaJasa, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                            Batal
                        </button>
                        <button wire:click="updateItem" 
                                class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 font-semibold transition-all duration-300">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Update Item</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Item Confirmation Modal -->
    @if($showDeleteItemConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-red-600">Konfirmasi Hapus Item</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Hapus Item {{ ucfirst($itemToDeleteType) }}</h4>
                            <p class="text-gray-600">Apakah Anda yakin ingin menghapus item ini?</p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <strong>Peringatan:</strong> 
                                @if($itemToDeleteType === 'barang')
                                    Stok barang akan dikembalikan ke inventory.
                                @else
                                    Item jasa akan dihapus dari transaksi.
                                @endif
                                Total transaksi akan diperbarui secara otomatis.
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                            Batal
                        </button>
                        <button wire:click="deleteItem" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 font-semibold transition-all duration-300">
                            Ya, Hapus Item
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


   <!-- Payment Detail Modal -->
    @if($showPaymentDetailModal && $selectedPayment)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $editingPayment ? 'Edit Pembayaran' : 'Detail Pembayaran' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($editingPayment)
                        <!-- Edit Payment Form -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar *</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                        <input wire:model="editPaymentAmount" type="number" min="1" step="1000" class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0">
                                    </div>
                                    @error('editPaymentAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                                    <select wire:model="editPaymentMethod" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="tunai">💵 Tunai</option>
                                        <option value="transfer">🏦 Transfer</option>
                                    </select>
                                    @error('editPaymentMethod') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar *</label>
                                <input wire:model="editPaymentDate" type="date" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('editPaymentDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                <textarea wire:model="editPaymentNote" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Keterangan pembayaran..."></textarea>
                                @error('editPaymentNote') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                                @if($currentPaymentProof)
                                    <div class="mb-3 p-3 bg-blue-50 rounded-xl">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-blue-700">📎 Bukti saat ini tersedia</span>
                                            <button type="button" wire:click="viewPaymentProof({{ $selectedPayment->id }})" class="text-blue-600 hover:text-blue-800 text-sm underline">
                                                Lihat Bukti
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <input wire:model="editPaymentProof" type="file" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max: 5MB)</p>
                                @error('editPaymentProof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @else
                        <!-- View Payment Details -->
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="text-lg font-semibold text-green-900 mb-3">Informasi Pembayaran</h4>
                                        <div class="space-y-2 text-sm">
                                            <div><strong>Jumlah:</strong> <span class="text-2xl font-bold text-green-600">Rp{{ number_format($selectedPayment->jumlah_bayar, 0, ',', '.') }}</span></div>
                                            <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($selectedPayment->tanggal_bayar)->format('d F Y') }}</div>
                                            <div><strong>Metode:</strong> 
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                                    {{ $selectedPayment->metode_pembayaran === 'tunai' ? '💵 TUNAI' : '🏦 TRANSFER' }}
                                                </span>
                                            </div>
                                            <div><strong>Kasir:</strong> {{ $selectedPayment->kasir }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-green-900 mb-3">Detail Tambahan</h4>
                                        <div class="space-y-2 text-sm">
                                            @if($selectedPayment->keterangan)
                                                <div class="bg-green-100 rounded-lg p-3">
                                                    <strong>Keterangan:</strong><br>
                                                    {{ $selectedPayment->keterangan }}
                                                </div>
                                            @endif
                                            @if($selectedPayment->bukti_bayar)
                                                <div class="bg-blue-100 rounded-lg p-3">
                                                    <strong>Bukti Pembayaran:</strong><br>
                                                    <button wire:click="viewPaymentProof({{ $selectedPayment->id }})" class="text-blue-600 hover:text-blue-800 underline mt-1">
                                                        📎 Lihat Bukti Pembayaran
                                                    </button>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 rounded-lg p-3">
                                                    <span class="text-gray-600">Tidak ada bukti pembayaran</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                            {{ $editingPayment ? 'Batal' : 'Tutup' }}
                        </button>

                        @if(!$editingPayment)
                            {{-- Tampilkan tombol Edit hanya saat mode detail --}}
                            <button wire:click="editPayment({{ $selectedPayment->id }})"
                                    class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 font-semibold transition-all duration-300">
                                Edit Pembayaran
                            </button>
                        @endif

                        @if($editingPayment)
                            {{-- Tampilkan tombol Update hanya saat mode edit --}}
                            <button wire:click="updatePayment({{ $selectedPayment->id }})"
                                    class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 font-semibold transition-all duration-300">
                                Update Pembayaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


        <!-- Payment Modal -->
    @if($showPaymentModal && $selectedTransaction)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 rounded-t-3xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Proses Pembayaran</h3>
                            <p class="text-white/80 text-sm">{{ $selectedTransaction->invoice }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-white/80 hover:text-white p-2 rounded-lg hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <div class="font-semibold mb-1">Terdapat kesalahan:</div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Payment Summary Card -->
                <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border border-blue-200 rounded-2xl p-6">
                    <div class="text-center space-y-3">
                        <div class="text-sm font-medium text-gray-600">Ringkasan Pembayaran</div>
                        
                        <!-- Total -->
                        <div class="space-y-2">
                            <div class="text-2xl font-bold text-gray-900">
                                Rp{{ number_format($selectedTransaction->total_keseluruhan, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">Total Keseluruhan</div>
                        </div>

                        <!-- Payment Progress -->
                        <div class="bg-white rounded-xl p-4 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Sudah Dibayar</span>
                                <span class="font-semibold text-green-600">
                                    Rp{{ number_format($selectedTransaction->total_sudah_dibayar, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $selectedTransaction->total_keseluruhan > 0 ? ($selectedTransaction->total_sudah_dibayar / $selectedTransaction->total_keseluruhan * 100) : 0 }}%">
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Sisa Pembayaran</span>
                                <span class="font-bold text-orange-600">
                                    Rp{{ number_format($selectedTransaction->sisa_pembayaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Check if already paid in full -->
                @if($selectedTransaction->status_pembayaran === 'lunas')
                    <!-- Paid in Full Status -->
                    <div class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 border-2 border-green-200 rounded-2xl p-8">
                        <div class="text-center space-y-4">
                            <!-- Animated Checkmark -->
                            <div class="flex justify-center">
                                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center animate-bounce">
                                    <svg class="w-10 h-10 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Status Text -->
                            <div class="space-y-2">
                                <h3 class="text-2xl font-bold text-green-800">LUNAS</h3>
                                <p class="text-green-600 font-medium">Pembayaran telah diselesaikan</p>
                                <div class="text-sm text-gray-600">
                                    Total dibayar: <span class="font-semibold text-green-700">Rp{{ number_format($selectedTransaction->total_sudah_dibayar, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Payment History Summary -->
                            @if($selectedTransaction->servicePayments && $selectedTransaction->servicePayments->count() > 0)
                            <div class="bg-white rounded-xl p-4 mt-4">
                                <div class="text-sm font-medium text-gray-700 mb-3">Riwayat Pembayaran</div>
                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                    @foreach($selectedTransaction->servicePayments as $payment)
                                    <div class="flex justify-between items-center text-xs py-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                            <span>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d/m/Y') }}</span>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ $payment->metode_pembayaran }}</span>
                                        </div>
                                        <span class="font-semibold text-green-600">Rp{{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Payment Form (Only show if not fully paid) -->
                    <div class="bg-gray-50 rounded-2xl p-5 space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input wire:model.live="jumlahBayar"
                                        type="number"
                                        min="1"
                                        max="{{ $selectedTransaction->sisa_pembayaran }}"
                                        class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 font-medium text-lg"
                                        placeholder="0">
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Maksimal: Rp{{ number_format($selectedTransaction->sisa_pembayaran, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                                <input wire:model="tanggalBayar"
                                    type="date"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Metode Pembayaran</label>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                <div class="cursor-pointer {{ $metodePembayaran === 'tunai' ? 'ring-2 ring-green-500' : '' }}"
                                    wire:click="$set('metodePembayaran', 'tunai')">
                                    <div class="p-4 border-2 rounded-xl {{ $metodePembayaran === 'tunai' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white' }} hover:border-green-300 transition-all">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">💵 Tunai</div>
                                                <div class="text-sm text-gray-500">Pembayaran cash</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="cursor-pointer {{ $metodePembayaran === 'transfer' ? 'ring-2 ring-blue-500' : '' }}"
                                    wire:click="$set('metodePembayaran', 'transfer')">
                                    <div class="p-4 border-2 rounded-xl {{ $metodePembayaran === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }} hover:border-blue-300 transition-all">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">🏦 Transfer</div>
                                                <div class="text-sm text-gray-500">Bank transfer</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Pembayaran 
                                <span class="text-gray-500 text-xs">(Opsional)</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gray-400 transition-colors">
                                <input wire:model="buktiPembayaran"
                                    type="file"
                                    accept="image/*,.pdf"
                                    class="hidden"
                                    id="bukti-pembayaran">
                                
                                @if($buktiPembayaran)
                                    <div class="space-y-3">
                                        <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mx-auto">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $buktiPembayaran->getClientOriginalName() }}</div>
                                            <div class="text-xs text-gray-500">{{ number_format($buktiPembayaran->getSize() / 1024, 1) }} KB</div>
                                        </div>
                                        <button wire:click="$set('buktiPembayaran', null)"
                                                class="text-red-600 hover:text-red-700 text-sm font-medium">
                                            Hapus File
                                        </button>
                                    </div>
                                @else
                                    <label for="bukti-pembayaran" class="cursor-pointer">
                                        <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">Upload Bukti Pembayaran</div>
                                        <div class="text-xs text-gray-500 mt-1">PNG, JPG, PDF hingga 5MB</div>
                                    </label>
                                @endif
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                            <textarea wire:model="keteranganPembayaran"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Catatan atau keterangan tambahan..."></textarea>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button wire:click="closeModal"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors order-2 sm:order-1">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>{{ $selectedTransaction->status_pembayaran === 'lunas' ? 'Tutup' : 'Batal' }}</span>
                        </span>
                    </button>
                    
                    @if($selectedTransaction->status_pembayaran !== 'lunas')
                    <button wire:click="addPayment"
                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 font-semibold transition-all duration-300 order-1 sm:order-2">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Proses Pembayaran</span>
                        </span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-red-600">Konfirmasi Hapus</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Hapus Transaksi</h4>
                            <p class="text-gray-600">Apakah Anda yakin ingin menghapus transaksi ini?</p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data terkait transaksi akan dihapus dan stok barang akan dikembalikan.
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                            Batal
                        </button>
                        <button wire:click="deleteTransaction" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 font-semibold transition-all duration-300">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

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

    Livewire.on('item-added', (data) => {
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

// Enhanced table interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states
    const buttons = document.querySelectorAll('button[wire\\:click]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!button.disabled) {
                button.style.opacity = '0.7';
                button.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    button.style.opacity = '';
                    button.style.transform = '';
                }, 200);
            }
        });
    });

    // Auto-refresh functionality
    let refreshInterval;
    const startAutoRefresh = () => {
        refreshInterval = setInterval(() => {
            @this.call('$refresh');
        }, 30000); // Refresh every 30 seconds
    };

    const stopAutoRefresh = () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    };

    // Start auto refresh when page is visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });

    // Start auto refresh
    startAutoRefresh();

    // Stop auto refresh when modal is open
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            const modals = document.querySelectorAll('.fixed.inset-0.bg-black');
            if (modals.length > 0) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// Enhanced error handling
window.addEventListener('livewire:init', () => {
    Livewire.hook('morph.failed', (el, component) => {
        showToastMessage('Error!', 'Terjadi kesalahan saat memperbarui data', 'error');
    });

    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                showToastMessage('Session Expired', 'Silakan refresh halaman dan login kembali', 'warning');
            } else if (status === 500) {
                showToastMessage('Server Error', 'Terjadi kesalahan server, silakan coba lagi', 'error');
            }
        });
    });
});

console.log('🚗 Riwayat Transaksi Service loaded successfully!');
console.log('🔧 Features: View, Edit, Add Items, Edit Items, Delete Items, Add Payments, Edit Payments, Manual Items, Print Invoice, Delete');
console.log('⌨️ Keyboard shortcuts: Escape (close modal), Ctrl+N (new transaction), Ctrl+F (focus search)');
console.log('🔄 Auto-refresh: Every 30 seconds when page is visible');
console.log('✏️ UPDATED: Edit and delete buttons now ALWAYS VISIBLE for better user experience');
console.log('💰 NEW: Payment detail/edit modal with photo proof support');
console.log('🔧 NEW: Manual item support for indent/order items');
</script>
