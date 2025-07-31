@push('styles')
<link rel="stylesheet" href="{{ asset('css/print-detail-transaksi-barang.css') }}">
@endpush

<div class="mt-16 bg-gradient-to-br from-slate-50 to-slate-100 p-4 lg:p-6">
    {{-- Header Section --}}
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
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-600 text-sm font-medium">Total Transaksi</p>
                                <p class="text-2xl font-bold text-blue-900 mt-1">{{ number_format($totalTransaksi) }}</p>
                            </div>
                            <div class="p-3 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-emerald-600 text-sm font-medium">Total Penjualan</p>
                                <p class="text-2xl font-bold text-emerald-900 mt-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-emerald-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-600 text-sm font-medium">Total Profit</p>
                                <p class="text-2xl font-bold text-amber-900 mt-1">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-amber-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-600 text-sm font-medium">Rata-rata</p>
                                <p class="text-2xl font-bold text-purple-900 mt-1">Rp {{ $totalTransaksi > 0 ? number_format($totalPenjualan / $totalTransaksi, 0, ',', '.') : 0 }}</p>
                            </div>
                            <div class="p-3 bg-purple-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Filter Buttons --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    <button wire:click="setTanggalHariIni" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Hari Ini
                    </button>
                    <button wire:click="setTanggalMingguIni" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors duration-200">
                        Minggu Ini
                    </button>
                    <button wire:click="setTanggalBulanIni" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors duration-200">
                        Bulan Ini
                    </button>
                </div>

                {{-- Advanced Filters --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search" 
                                   class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="Cari kasir, keterangan, atau barang...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Mulai</label>
                        <input type="date" 
                               wire:model.live="tanggal_mulai" 
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Selesai</label>
                        <input type="date" 
                               wire:model.live="tanggal_selesai" 
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Kasir</label>
                        <select wire:model.live="kasir_filter" 
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Semua Kasir</option>
                            @foreach($kasirList as $kasir)
                                <option value="{{ $kasir }}">{{ $kasir }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col justify-end">
                        <div class="flex gap-2">
                            <button wire:click="resetFilter" 
                                    class="flex-1 px-4 py-2.5 bg-slate-500 hover:bg-slate-600 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </button>
                            <select wire:model.live="per_page" 
                                    class="px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        @if($transaksis->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th wire:click="sortBy('id')" class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    ID
                                    @if($sortBy === 'id')
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('tanggal')" class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Tanggal
                                    @if($sortBy === 'tanggal')
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('kasir')" class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Kasir
                                    @if($sortBy === 'kasir')
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Items</th>
                            <th wire:click="sortBy('total_harga')" class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Total
                                    @if($sortBy === 'total_harga')
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($transaksis as $transaksi)
                            <tr class="hover:bg-slate-50 transition-colors duration-150" data-transaksi-id="{{ $transaksi->id }}" wire:key="transaksi-{{ $transaksi->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        #{{ $transaksi->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ $transaksi->tanggal->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $transaksi->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900">{{ $transaksi->kasir }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800 w-fit">
                                            {{ $transaksi->itemPenjualan->count() }} item(s)
                                        </span>
                                        <div class="mt-2 space-y-1">
                                            @foreach($transaksi->itemPenjualan->take(2) as $item)
                                                <div class="text-xs text-slate-600 flex items-center gap-1">
                                                    <div class="w-1 h-1 bg-slate-400 rounded-full"></div>
                                                    {{ $item->barang->nama }} ({{ $item->jumlah }})
                                                </div>
                                            @endforeach
                                            @if($transaksi->itemPenjualan->count() > 2)
                                                <div class="text-xs text-slate-500">
                                                    +{{ $transaksi->itemPenjualan->count() - 2 }} lainnya
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-emerald-600">
                                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 max-w-xs truncate">
                                        {{ $transaksi->keterangan ?: '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="lihatDetail({{ $transaksi->id }})" 
                                                class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                                title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="hapusTransaksiLangsung({{ $transaksi->id }})" 
                                                wire:confirm="Yakin hapus transaksi #{{ $transaksi->id }}? Stok akan dikembalikan ke pembelian."
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                title="Hapus Transaksi">
                                            <svg wire:loading.remove wire:target="hapusTransaksiLangsung({{ $transaksi->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <svg wire:loading wire:target="hapusTransaksiLangsung({{ $transaksi->id }})" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700">
                        Menampilkan {{ $transaksis->firstItem() }} - {{ $transaksis->lastItem() }} 
                        dari {{ $transaksis->total() }} transaksi
                    </div>
                    <div>
                        {{ $transaksis->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 text-slate-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-slate-900 mb-2">Tidak ada transaksi ditemukan</h3>
                <p class="text-slate-600 mb-6">Coba ubah filter pencarian atau tambahkan transaksi baru</p>
                <button wire:click="resetFilter" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Reset Filter
                </button>
            </div>
        @endif
    </div>

    {{-- Modal Detail Transaksi --}}
    @if($showDetailModal && $selectedTransaksi)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" wire:click="tutupDetail">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    {{-- Header Modal --}}
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">
                                    Detail Transaksi #{{ $selectedTransaksi->id }}
                                </h3>
                            </div>
                            <button wire:click="tutupDetail" class="p-2 hover:bg-white/20 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content Modal --}}
                    <div class="px-6 py-6">
                        {{-- Informasi Transaksi --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Informasi Transaksi
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">ID Transaksi:</span>
                                        <span class="font-semibold text-slate-900">#{{ $selectedTransaksi->id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Tanggal:</span>
                                        <span class="font-semibold text-slate-900">{{ $selectedTransaksi->tanggal->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Kasir:</span>
                                        <span class="font-semibold text-slate-900">{{ $selectedTransaksi->kasir }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Keterangan:</span>
                                        <span class="font-semibold text-slate-900">{{ $selectedTransaksi->keterangan ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                                <h4 class="text-lg font-semibold text-emerald-800 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Ringkasan Keuangan
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-emerald-700">Total Transaksi:</span>
                                        <span class="text-2xl font-bold text-emerald-800">
                                            Rp {{ number_format($selectedTransaksi->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-emerald-700">Total Profit:</span>
                                        <span class="text-lg font-bold text-emerald-600">
                                            Rp {{ number_format(collect($detailItems)->sum('profit'), 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-emerald-700">Jumlah Item:</span>
                                        <span class="text-lg font-semibold text-emerald-800">
                                            {{ collect($detailItems)->sum('jumlah') }} unit
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Items --}}
                        <div>
                            <h4 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Detail Barang
                            </h4>
                            
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Barang</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Qty</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Harga Jual</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Subtotal</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Harga Beli</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Profit</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Margin</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Supplier</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-200">
                                            @foreach($detailItems as $item)
                                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                                    <td class="px-4 py-4">
                                                        <div class="text-sm font-medium text-slate-900">{{ $item['barang_nama'] }}</div>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $item['jumlah'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-right text-sm text-slate-900">
                                                        Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-4 text-right">
                                                        <span class="text-sm font-bold text-slate-900">
                                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-right text-sm text-slate-600">
                                                        Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-4 text-right">
                                                        <span class="text-sm font-semibold {{ $item['profit'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                            Rp {{ number_format($item['profit'], 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item['profit_margin'] >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ number_format($item['profit_margin'], 1) }}%
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-sm text-slate-600">{{ $item['supplier'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-slate-50">
                                            <tr>
                                                <th colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-slate-900">Total:</th>
                                                <th class="px-4 py-3 text-right text-sm font-bold text-slate-900">
                                                    Rp {{ number_format(collect($detailItems)->sum('subtotal'), 0, ',', '.') }}
                                                </th>
                                                <th class="px-4 py-3"></th>
                                                <th class="px-4 py-3 text-right text-sm font-bold {{ collect($detailItems)->sum('profit') >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                    Rp {{ number_format(collect($detailItems)->sum('profit'), 0, ',', '.') }}
                                                </th>
                                                <th colspan="2" class="px-4 py-3"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 print:hidden">
                        <button wire:click="tutupDetail" 
                                class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg font-medium transition-colors duration-200">
                            Tutup
                        </button>
                        <button onclick="printDetailTransaksi()" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Hidden Print Layout --}}
    @if($showDetailModal && $selectedTransaksi)
        <div id="print-content" class="hidden print:block print:fixed print:inset-0 print:bg-white print:p-8">
            {{-- Print Header --}}
            <div class="text-center mb-8 border-b-2 border-slate-300 pb-6">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">DETAIL TRANSAKSI</h1>
                <h2 class="text-xl font-semibold text-slate-700">Transaksi #{{ $selectedTransaksi->id }}</h2>
                <p class="text-slate-600 mt-2">{{ now()->format('d F Y, H:i') }}</p>
            </div>

            {{-- Print Info Section --}}
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 border-b border-slate-200 pb-2">Informasi Transaksi</h3>
                    <table class="w-full text-sm">
                        <tr class="border-b border-slate-100">
                            <td class="py-2 font-medium text-slate-700 w-32">ID Transaksi:</td>
                            <td class="py-2 text-slate-900">#{{ $selectedTransaksi->id }}</td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-2 font-medium text-slate-700">Tanggal:</td>
                            <td class="py-2 text-slate-900">{{ $selectedTransaksi->tanggal->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-2 font-medium text-slate-700">Kasir:</td>
                            <td class="py-2 text-slate-900">{{ $selectedTransaksi->kasir }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium text-slate-700">Keterangan:</td>
                            <td class="py-2 text-slate-900">{{ $selectedTransaksi->keterangan ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 border-b border-slate-200 pb-2">Ringkasan Keuangan</h3>
                    <table class="w-full text-sm">
                        <tr class="border-b border-slate-100">
                            <td class="py-2 font-medium text-slate-700 w-32">Total Transaksi:</td>
                            <td class="py-2 text-slate-900 font-bold">Rp {{ number_format($selectedTransaksi->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-2 font-medium text-slate-700">Total Profit:</td>
                            <td class="py-2 text-slate-900 font-semibold">Rp {{ number_format(collect($detailItems)->sum('profit'), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium text-slate-700">Jumlah Item:</td>
                            <td class="py-2 text-slate-900">{{ collect($detailItems)->sum('jumlah') }} unit</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Print Detail Items --}}
            <div>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 border-b border-slate-200 pb-2">Detail Barang</h3>
                
                <table class="w-full border-collapse border border-slate-300 text-sm">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="border border-slate-300 px-3 py-2 text-left font-semibold">Barang</th>
                            <th class="border border-slate-300 px-3 py-2 text-center font-semibold">Qty</th>
                            <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Harga Jual</th>
                            <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Subtotal</th>
                            <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Harga Beli</th>
                            <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Profit</th>
                            <th class="border border-slate-300 px-3 py-2 text-center font-semibold">Margin</th>
                            <th class="border border-slate-300 px-3 py-2 text-left font-semibold">Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailItems as $item)
                            <tr class="border-b border-slate-200">
                                <td class="border border-slate-300 px-3 py-2">{{ $item['barang_nama'] }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-center">{{ $item['jumlah'] }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right">Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right">Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right font-semibold {{ $item['profit'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                    Rp {{ number_format($item['profit'], 0, ',', '.') }}
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-center">{{ number_format($item['profit_margin'], 1) }}%</td>
                                <td class="border border-slate-300 px-3 py-2">{{ $item['supplier'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-100 font-bold">
                            <td colspan="3" class="border border-slate-300 px-3 py-2 text-right">TOTAL:</td>
                            <td class="border border-slate-300 px-3 py-2 text-right">Rp {{ number_format(collect($detailItems)->sum('subtotal'), 0, ',', '.') }}</td>
                            <td class="border border-slate-300 px-3 py-2"></td>
                            <td class="border border-slate-300 px-3 py-2 text-right {{ collect($detailItems)->sum('profit') >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                Rp {{ number_format(collect($detailItems)->sum('profit'), 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="border border-slate-300 px-3 py-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Print Footer --}}
            <div class="mt-8 pt-6 border-t border-slate-300 text-center text-sm text-slate-600">
                <p>Dicetak pada: {{ now()->format('d F Y, H:i:s') }}</p>
                <p class="mt-1">Sistem Manajemen Penjualan</p>
            </div>
        </div>
    @endif

    {{-- Toast Notifications --}}
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 z-50" id="success-toast">
            <div class="bg-white rounded-lg shadow-lg border border-emerald-200 p-4 flex items-center gap-3 max-w-sm">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-900">Sukses!</p>
                    <p class="text-sm text-slate-600">{{ session('message') }}</p>
                </div>
                <button onclick="document.getElementById('success-toast').remove()" class="flex-shrink-0 text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="fixed top-4 right-4 z-50" id="error-toast">
            <div class="bg-white rounded-lg shadow-lg border border-red-200 p-4 flex items-center gap-3 max-w-sm">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-900">Error!</p>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-slate-600">{{ $error }}</p>
                    @endforeach
                </div>
                <button onclick="document.getElementById('error-toast').remove()" class="flex-shrink-0 text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript --}}
<script>
    document.addEventListener('livewire:init', function () {
        // Confirmation dialog for delete
        Livewire.on('confirm-delete', (event) => {
            const data = event;
            if (confirm(`${data.text}\n\nPeringatan: Aksi ini akan mengembalikan stok barang ke pembelian.`)) {
                @this.call('hapusTransaksi', data.transaksiId);
            }
        });

        // Listen for refresh events
        Livewire.on('refresh-component', () => {
            // Force re-render component
            @this.$refresh();
        });

        // Listen for transaction deleted event
        Livewire.on('transaksi-deleted', (event) => {
            // Remove row animation (optional)
            const row = document.querySelector(`[data-transaksi-id="${event.transaksiId}"]`);
            if (row) {
                row.style.opacity = '0.5';
                row.style.transform = 'translateX(-100%)';
                setTimeout(() => {
                    @this.$refresh();
                }, 300);
            } else {
                // Fallback refresh
                setTimeout(() => {
                    @this.$refresh();
                }, 100);
            }
        });
    });

    // Enhanced Print Function
    function printDetailTransaksi() {
        // Hide modal backdrop and show print content
        const modal = document.querySelector('.fixed.inset-0.z-50');
        const printContent = document.getElementById('print-content');
        
        if (modal) modal.style.display = 'none';
        if (printContent) printContent.classList.remove('hidden');
        
        // Print with callback
        window.print();
        
        // Restore modal after print
        setTimeout(() => {
            if (modal) modal.style.display = 'flex';
            if (printContent) printContent.classList.add('hidden');
        }, 100);
    }

    // Handle print events
    window.addEventListener('beforeprint', function() {
        // Ensure print content is visible
        const printContent = document.getElementById('print-content');
        if (printContent) {
            printContent.classList.remove('hidden');
            printContent.style.display = 'block';
        }
        
        // Hide everything else
        document.body.classList.add('printing');
    });

    window.addEventListener('afterprint', function() {
        // Restore normal view
        const printContent = document.getElementById('print-content');
        if (printContent) {
            printContent.classList.add('hidden');
            printContent.style.display = 'none';
        }
        
        document.body.classList.remove('printing');
    });
    
    // Auto hide toasts
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('[id$="-toast"]');
        toasts.forEach(toast => {
            setTimeout(() => {
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        });
    });

    // Force refresh on window focus (backup solution)
    window.addEventListener('focus', function() {
        // Optional: refresh when user comes back to tab
        // @this.$refresh();
    });
</script>

