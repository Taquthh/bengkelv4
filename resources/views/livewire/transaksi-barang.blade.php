<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 mt-16">
    <div class="max-w-8xl mx-auto">
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

        <!-- Main Content Layout -->
        <div class="flex gap-6">
            <!-- Left Side: Header + Product Selection -->
            <div class="flex-1">
                <!-- Header -->
                <div tyle="height: 800px" class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-5 mb-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent">
                                    Transaksi Barang
                                </h1>
                                <p class="text-gray-500 font-medium text-base">{{ now()->format('l, d F Y • H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl px-6 py-3 border border-blue-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium">Kasir</p>
                                        <input wire:model="kasir" type="text" readonly 
                                               class="bg-transparent font-semibold text-gray-900 border-none p-0 text-base focus:ring-0 w-28" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl px-6 py-3 border border-purple-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium">Catatan</p>
                                        <input wire:model="keterangan" type="text" 
                                               class="bg-transparent font-semibold text-gray-900 border-none p-0 text-base focus:ring-0 w-36" 
                                               placeholder="Tambah catatan..." />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Selection Area -->
                <div style="height: 670px" class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 h-[calc(100vh-260px)]">
                    <!-- Search Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Daftar Produk</h2>
                            <p class="text-gray-500 text-base">{{ count($barangs) }} item tersedia</p>
                        </div>
                        <div class="relative w-80">
                            <input type="text" 
                                   x-data="{ search: '' }" 
                                   x-model="search" 
                                   @input="filterItems($event.target.value)"
                                   placeholder="Cari produk, merk, atau tipe..." 
                                   class="w-full px-6 py-4 pl-14 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-300 text-gray-900 placeholder-gray-400 shadow-sm text-base">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-4 gap-4 overflow-y-auto custom-scrollbar pr-2" style="height: calc(100% - 120px);" id="itemsGrid">
                        @foreach($barangs as $barang)
                            @php
                                $totalStok = $barang->pembelians->sum('jumlah_tersisa');
                                $supplierCount = $barang->pembelians->groupBy('supplier')->count();
                                $avgHPP = $barang->pembelians->avg('harga_beli');
                                $isOutOfStock = $totalStok <= 0;
                            @endphp
                            <div class="group bg-gradient-to-br from-white to-gray-50 rounded-2xl p-5 border border-gray-100 transition-all duration-300 cursor-pointer item-card
                                        {{ $isOutOfStock ? 'opacity-60 hover:opacity-75' : 'hover:border-blue-300 hover:shadow-xl transform hover:-translate-y-1' }}"
                                 onclick="{{ $isOutOfStock ? '' : "selectItem({$barang->id}, '" . addslashes($barang->nama) . "', {$totalStok}, " . ($avgHPP ?? 0) . ")" }}"
                                 data-nama="{{ strtolower($barang->nama) }}"
                                 data-merk="{{ strtolower($barang->merk ?? '') }}"
                                 data-tipe="{{ strtolower($barang->tipe ?? '') }}"
                                 data-deskripsi="{{ strtolower($barang->deskripsi ?? '') }}">
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 text-base leading-tight mb-2 {{ $isOutOfStock ? 'text-gray-500' : 'group-hover:text-blue-600' }} transition-colors">
                                            {{ $barang->nama }}
                                        </h4>
                                        <div class="text-sm text-gray-500 space-y-1">
                                            @if($barang->merk)
                                                <div class="flex items-center space-x-2">
                                                    <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                                                    <span class="truncate">{{ $barang->merk }}</span>
                                                </div>
                                            @endif
                                            @if($barang->tipe)
                                                <div class="flex items-center space-x-2">
                                                    <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                                                    <span class="truncate">{{ $barang->tipe }}</span>
                                                </div>
                                            @endif
                                            @if($barang->satuan)
                                                <div class="flex items-center space-x-2">
                                                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                                    <span class="truncate">{{ $barang->satuan }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right ml-3">
                                        @if($isOutOfStock)
                                            <div class="bg-gradient-to-r from-red-100 to-pink-100 text-red-800 text-sm font-bold px-3 py-2 rounded-xl">
                                                HABIS
                                            </div>
                                        @else
                                            <div class="bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 text-sm font-bold px-3 py-2 rounded-xl">
                                                {{ $totalStok }}
                                            </div>
                                        @endif
                                        <div class="text-sm text-gray-400 mt-1 font-medium">{{ $supplierCount }} supplier</div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <div class="text-sm text-gray-500 font-medium">
                                        💰Hpp Rp{{ number_format(($avgHPP ?? 0)/1000, 0) }}k
                                    </div>
                                    @if($isOutOfStock)
                                        <div class="bg-gray-200 text-gray-500 text-sm font-bold px-4 py-2 rounded-xl">
                                            STOK HABIS
                                        </div>
                                    @else
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm font-bold px-4 py-2 rounded-xl group-hover:from-blue-600 group-hover:to-purple-600 transition-all duration-300 shadow-lg">
                                            PILIH
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Cart & Order Panel -->
            <div class="w-[420px] flex-shrink-0">
                <!-- Combined Order Summary & Shopping Cart in Grid -->
                <div style="height: 800px" class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 h-[calc(100vh-140px)] flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Ringkasan & Keranjang</h3>
                            <p class="text-gray-500 text-base">Detail transaksi saat ini</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 via-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="grid grid-cols-3 gap-3 mb-6">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-3 border border-blue-100">
                            <div class="text-center">
                                <div class="text-xl font-bold text-blue-600">{{ count($items) }}</div>
                                <div class="text-xs text-blue-500 font-medium">Item</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-3 border border-purple-100">
                            <div class="text-center">
                                <div class="text-xl font-bold text-purple-600">{{ collect($items)->sum('jumlah') }}</div>
                                <div class="text-xs text-purple-500 font-medium">Jumlah</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-3 border border-emerald-100">
                            <div class="text-center">
                                <div class="text-base font-bold text-emerald-600">
                                    Rp{{ number_format(collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga_jual'])/1000, 0) }}k
                                </div>
                                <div class="text-xs text-emerald-500 font-medium">Total</div>
                            </div>
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-semibold text-gray-700">Total Keseluruhan</span>
                            <span class="text-xl font-bold bg-gradient-to-r from-green-600 via-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Rp{{ number_format(collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga_jual']), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Cart Items - Flex Grow -->
                    @if(count($items) > 0)
                        <div class="flex-1 min-h-0 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-bold text-gray-900">Item dalam Keranjang</h4>
                                <div class="bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-2xl">
                                    {{ count($items) }} item
                                </div>
                            </div>
                            
                            <!-- Scrollable Cart Items -->
                            {{-- style="height: 280px" --}}
                            <div style="height: 370px" class="space-y-2 overflow-y-auto custom-scrollbar pr-2 h-full">
                                @foreach($items as $index => $item)
                                    <div class="bg-gradient-to-r from-gray-50 via-white to-gray-50 rounded-xl p-3 border border-gray-200 group hover:from-blue-50 hover:via-white hover:to-purple-50 hover:border-blue-300 transition-all duration-300 shadow-sm hover:shadow-lg">
                                        <!-- Optimized Layout -->
                                        <div class="space-y-2">
                                            <!-- Product Name Row -->
                                            <div class="flex justify-between items-start gap-2">
                                                <h5 class="font-bold text-gray-900 text-sm leading-tight group-hover:text-blue-600 transition-colors flex-1 min-w-0">
                                                    <span class="block truncate">{{ $item['nama'] }}</span>
                                                </h5>
                                                <!-- Delete Button - Fixed Size -->
                                                <button wire:click="hapusItem({{ $index }})" 
                                                        class="w-7 h-7 bg-gradient-to-r from-red-100 to-pink-100 text-red-600 rounded-lg hover:from-red-200 hover:to-pink-200 transition-all duration-300 flex items-center justify-center group-hover:scale-110 shadow-sm hover:shadow-md flex-shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Quantity and Price Row -->
                                            <div class="flex justify-between items-center">
                                                <!-- Quantity Badge -->
                                                <div class="bg-blue-100 text-blue-800 px-2 py-1 rounded-lg font-semibold text-xs">
                                                    {{ $item['jumlah'] }} qty
                                                </div>
                                                
                                                <!-- Total Price -->
                                                <div class="text-sm font-bold text-emerald-600">
                                                    Rp{{ number_format($item['jumlah'] * $item['harga_jual'], 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Empty Cart State -->
                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6.5-5v5m0-5h4"></path>
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-600 mb-2">Keranjang Kosong</h4>
                                <p class="text-sm text-gray-500">Pilih produk untuk mulai transaksi</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons Area - Fixed Bottom -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <!-- Payment Method Selector (Future Addition) -->
                        {{-- @if(count($items) > 0)
                            <div class="flex gap-2 mb-3">
                                <button class="flex-1 px-3 py-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 text-sm font-medium border border-blue-200">
                                    💳 Tunai
                                </button>
                                <button class="flex-1 px-3 py-2 bg-gray-50 text-gray-500 rounded-xl hover:bg-gray-100 transition-all duration-200 text-sm font-medium border border-gray-200">
                                    📱 Digital
                                </button>
                                <button class="flex-1 px-3 py-2 bg-gray-50 text-gray-500 rounded-xl hover:bg-gray-100 transition-all duration-200 text-sm font-medium border border-gray-200">
                                    💳 Kartu
                                </button>
                            </div>
                        @endif --}}

                        <!-- Main Action Button -->
                        @if(count($items) > 0)
                            <button wire:click="simpanPenjualan" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 text-white rounded-3xl hover:from-green-600 hover:via-emerald-600 hover:to-teal-600 font-bold text-base transition-all duration-300 shadow-2xl shadow-green-500/25 flex items-center justify-center space-x-3 transform hover:scale-[1.02]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>SELESAIKAN PENJUALAN</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        @else
                            <button disabled
                                    class="w-full px-6 py-4 bg-gray-200 text-gray-400 rounded-3xl cursor-not-allowed font-bold text-base flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>TAMBAH ITEM UNTUK MELANJUTKAN</span>
                            </button>
                        @endif

                        @error('general') 
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm font-medium">
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Add Form - Bottom Section -->
        <div wire:ignore.self 
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 w-full max-w-5xl {{ $selectedBarangInfo ? '' : 'hidden' }}" 
             id="selectedItemForm">
            <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/40 p-7 mx-4">
                @if($selectedBarangInfo)
                    <div class="grid grid-cols-16 gap-6 items-center">
                        <!-- Product Info -->
                        <div class="col-span-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-18 h-18 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $selectedBarangInfo['nama'] }}</h3>
                                    <div class="flex items-center space-x-3">
                                        <span class="bg-emerald-100 text-emerald-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            📦 {{ $selectedBarangInfo['total_stok'] }} stok
                                        </span>
                                        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            🏪 {{ count($selectedBarangInfo['suppliers']) }} supplier
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Supplier Info -->
                        <div class="col-span-3">
                            <div class="space-y-2 max-h-24 overflow-y-auto custom-scrollbar">
                                @foreach($selectedBarangInfo['suppliers'] as $supplier)
                                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl px-4 py-3 text-sm border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-700 truncate">{{ $supplier['supplier'] }}</span>
                                            <span class="text-blue-600 font-bold text-base">{{ $supplier['stok'] }}</span>
                                        </div>
                                        <span class="text-gray-500">Rata-rata: Rp{{ number_format($supplier['harga_beli_rata']/1000, 0) }}k</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Input Forms -->
                        <div class="col-span-3 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-base font-bold text-gray-700 mb-3">Jumlah</label>
                                <input wire:model="jumlah" type="number" min="1" step="1"
                                       class="w-full px-5 py-4 bg-white border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 font-semibold text-center text-xl" 
                                       placeholder="0" />
                                @error('jumlah') <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-base font-bold text-gray-700 mb-3">Harga Jual</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold text-base">Rp</span>
                                    <input wire:model="harga_jual" type="number" min="0" step="0.01"
                                           class="w-full pl-12 pr-5 py-4 bg-white border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 font-semibold text-center text-xl" 
                                           placeholder="0" />
                                </div>
                                @error('harga_jual') <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-span-2 flex gap-3">
                            <button onclick="resetForm()" 
                                    class="flex-1 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-2xl hover:from-gray-200 hover:to-gray-300 transition-all duration-200 font-bold text-base shadow-lg hover:shadow-xl transform hover:scale-105">
                                BATAL
                            </button>
                            <button wire:click="tambahItem" 
                                    class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white rounded-2xl hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transition-all duration-200 font-bold text-base shadow-2xl shadow-blue-500/25 transform hover:scale-105">
                                TAMBAH
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function selectItem(barangId, namaBarang, stok, avgHPP) {
    if (stok <= 0) {
        // Show out of stock message
        const toast = document.createElement('div');
        toast.className = 'fixed top-6 right-6 bg-gradient-to-r from-red-500 via-pink-500 to-red-500 text-white px-8 py-4 rounded-3xl shadow-2xl z-50 flex items-center space-x-4 transform translate-x-full border border-white/20 backdrop-blur-lg';
        toast.innerHTML = `
            <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div>
                <div class="font-bold text-lg">Stok Habis!</div>
                <div class="text-sm opacity-90">Item ini sedang tidak tersedia</div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Slide in animation
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.transition = 'transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
        }, 100);
        
        // Slide out and remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'transform 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
        
        return;
    }
    
    @this.call('selectBarang', barangId);
    
    setTimeout(() => {
        const form = document.getElementById('selectedItemForm');
        if (form) {
            form.classList.remove('hidden');
            // Auto-suggest sale price (30% markup)
            const suggestedPrice = Math.round(avgHPP * 1.3);
            // Focus on quantity input first
            setTimeout(() => {
                const qtyInput = form.querySelector('input[wire\\:model="jumlah"]');
                if (qtyInput) {
                    qtyInput.focus();
                    qtyInput.select();
                }
            }, 200);
        }
    }, 150);
}

function filterItems(query) {
    const items = document.querySelectorAll('.item-card');
    const searchTerm = query.toLowerCase();
    
    items.forEach((item, index) => {
        const nama = item.dataset.nama || '';
        const merk = item.dataset.merk || '';
        const tipe = item.dataset.tipe || '';
        const deskripsi = item.dataset.deskripsi || '';
        
        const isMatch = nama.includes(searchTerm) || 
                       merk.includes(searchTerm) || 
                       tipe.includes(searchTerm) || 
                       deskripsi.includes(searchTerm);
        
        if (isMatch) {
            item.style.display = 'block';
            item.style.animation = `fadeInUp 0.4s ease-out ${index * 0.05}s both`;
        } else {
            item.style.display = 'none';
        }
    });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Enter to add item when form is visible
    if (e.key === 'Enter' && !document.getElementById('selectedItemForm').classList.contains('hidden')) {
        e.preventDefault();
        @this.call('tambahItem');
    }
    
    // Escape to cancel selection
    if (e.key === 'Escape') {
        resetForm();
    }
});

document.addEventListener('livewire:init', () => {
    Livewire.on('item-added', () => {
        const form = document.getElementById('selectedItemForm');
        if (form) {
            form.classList.add('hidden');
        }
        
        // Enhanced toast notification with animation
        const toast = document.createElement('div');
        toast.className = 'fixed top-6 right-6 bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 text-white px-8 py-4 rounded-3xl shadow-2xl z-50 flex items-center space-x-4 transform translate-x-full border border-white/20 backdrop-blur-lg';
        toast.innerHTML = `
            <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <div class="font-bold text-lg">Item Ditambahkan!</div>
                <div class="text-sm opacity-90">Berhasil ditambahkan ke keranjang</div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Slide in animation
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.transition = 'transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
        }, 100);
        
        // Slide out and remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'transform 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
        
        // Add success pulse effect to cart
        const cartSection = document.querySelector('.w-\\[420px\\]');
        if (cartSection) {
            cartSection.style.animation = 'pulse 0.6s ease-in-out';
        }
    });
    
    // Listen for form reset/cancel events
    Livewire.on('form-reset', () => {
        const form = document.getElementById('selectedItemForm');
        if (form) {
            form.classList.add('hidden');
        }
    });
});

// Update the resetFormInputs function call
function resetForm() {
    @this.call('resetFormInputs');
    const form = document.getElementById('selectedItemForm');
    if (form) {
        form.classList.add('hidden');
    }
}
</script>

{{-- <style>
@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

@keyframes slideInFromBottom {
    from {
        opacity: 0;
        transform: translateY(100%) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

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

.item-card:hover {
    transform: translateY(-4px) scale(1.02);
}

.backdrop-blur-xl {
    backdrop-filter: blur(20px);
}

.backdrop-blur-2xl {
    backdrop-filter: blur(40px);
}

/* Enhanced focus states */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: transform, box-shadow, background-color, border-color, color, opacity;
    transition-duration: 0.2s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Grid item stagger animation */
.item-card {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}

.item-card:nth-child(1) { animation-delay: 0.1s; }
.item-card:nth-child(2) { animation-delay: 0.15s; }
.item-card:nth-child(3) { animation-delay: 0.2s; }
.item-card:nth-child(4) { animation-delay: 0.25s; }
.item-card:nth-child(5) { animation-delay: 0.3s; }
.item-card:nth-child(6) { animation-delay: 0.35s; }
.item-card:nth-child(7) { animation-delay: 0.4s; }
.item-card:nth-child(8) { animation-delay: 0.45s; }

/* Bottom form animation */
#selectedItemForm {
    animation: slideInFromBottom 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) when-shown;
}

/* Gradient text animation */
@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.bg-clip-text {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

/* Enhanced button hover effects */
button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

button:active {
    transform: translateY(0);
}

/* Glass morphism enhancement */
.bg-white\/90 {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.bg-white\/95 {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(40px);
    -webkit-backdrop-filter: blur(40px);
}

/* Out of stock styling */
.item-card.opacity-60 {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    cursor: not-allowed;
}

.item-card.opacity-60:hover {
    transform: none;
    box-shadow: none;
}
</style> --}}