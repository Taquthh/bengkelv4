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

        <!-- Header -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent">
                            Transaksi Service Mobil
                        </h1>
                        <p class="text-gray-500 font-medium text-lg">{{ now()->format('l, d F Y • H:i') }}</p>
                    </div>
                </div>
                
                <!-- Steps Indicator -->
                <div class="flex items-center space-x-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm
                                {{ $currentStep >= $i ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                {{ $i }}
                            </div>
                            @if($i < 4)
                                <div class="w-12 h-1 {{ $currentStep > $i ? 'bg-gradient-to-r from-blue-500 to-purple-500' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Step Content -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Main Content -->
            <div class="col-span-8">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 min-h-[600px]">
                    
                    <!-- Step 1: Data Pelanggan & Mobil -->
                    @if($currentStep == 1)
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Data Pelanggan & Mobil</h2>
                                    <p class="text-gray-500">Masukkan informasi pelanggan dan kendaraan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- Data Pelanggan -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Informasi Pelanggan</h3>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan *</label>
                                        <input wire:model="nama_pelanggan" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama pelanggan">
                                        @error('nama_pelanggan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP/Telepon</label>
                                        <input wire:model="kontak" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="08xxxxxxxxxx">
                                        @error('kontak') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pelanggan</label>
                                        <select wire:model="jenis_pelanggan" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="perorangan">Perorangan</option>
                                            <option value="perusahaan">Perusahaan</option>
                                        </select>
                                    </div>

                                    @if($jenis_pelanggan == 'perusahaan')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                                            <input wire:model="nama_perusahaan" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="PT. Nama Perusahaan">
                                        </div>
                                    @endif
                                </div>

                                <!-- Data Mobil -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Informasi Kendaraan</h3>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Polisi *</label>
                                        <input wire:model.debounce.500ms="nopol" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="B 1234 ABC" style="text-transform: uppercase;">
                                        @error('nopol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        <p class="text-xs text-gray-500 mt-1">Ketik nomor polisi untuk mengisi data otomatis jika sudah terdaftar</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Merk Mobil *</label>
                                        <input wire:model="merk_mobil" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Toyota, Honda, dll">
                                        @error('merk_mobil') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe/Model *</label>
                                        <input wire:model="tipe_mobil" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Avanza, Civic, dll">
                                        @error('tipe_mobil') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                                            <input wire:model="tahun" type="number" min="1980" max="{{ date('Y') + 1 }}" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="2020">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                                            <input wire:model="warna" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Hitam, Putih, dll">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Mobil</label>
                                        <textarea wire:model="catatan_mobil" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan khusus tentang kendaraan"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Keluhan -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">Keluhan Pelanggan *</h3>
                                <textarea wire:model="keluhan" rows="4" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsikan keluhan atau masalah kendaraan..."></textarea>
                                @error('keluhan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Step 2: Pilih Barang -->
                    @if($currentStep == 2)
                        <div class="space-y-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">Pilih Barang</h2>
                                        <p class="text-gray-500">{{ count($barangs) }} item tersedia</p>
                                    </div>
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
                            <div class="grid grid-cols-4 gap-4 overflow-y-auto custom-scrollbar pr-2" style="height: 500px;" id="itemsGrid">
                                @foreach($barangs as $barang)
                                    @php
                                        $isOutOfStock = $barang['total_stok'] <= 0;
                                    @endphp
                                    <div class="group bg-gradient-to-br from-white to-gray-50 rounded-2xl p-5 border border-gray-100 transition-all duration-300 cursor-pointer item-card
                                                {{ $isOutOfStock ? 'opacity-60 hover:opacity-75' : 'hover:border-blue-300 hover:shadow-xl transform hover:-translate-y-1' }}"
                                         onclick="{{ $isOutOfStock ? '' : "selectItem({$barang['id']}, '" . addslashes($barang['nama']) . "', {$barang['total_stok']}, " . ($barang['avg_hpp'] ?? 0) . ")" }}"
                                         data-nama="{{ strtolower($barang['nama']) }}"
                                         data-merk="{{ strtolower($barang['merk'] ?? '') }}"
                                         data-tipe="{{ strtolower($barang['tipe'] ?? '') }}"
                                         data-deskripsi="{{ strtolower($barang['deskripsi'] ?? '') }}">
                                        
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-900 text-base leading-tight mb-2 {{ $isOutOfStock ? 'text-gray-500' : 'group-hover:text-blue-600' }} transition-colors">
                                                    {{ $barang['nama'] }}
                                                </h4>
                                                <div class="text-sm text-gray-500 space-y-1">
                                                    @if($barang['merk'])
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                                                            <span class="truncate">{{ $barang['merk'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if($barang['tipe'])
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                                                            <span class="truncate">{{ $barang['tipe'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if($barang['satuan'])
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                                            <span class="truncate">{{ $barang['satuan'] }}</span>
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
                                                        {{ $barang['total_stok'] }}
                                                    </div>
                                                @endif
                                                <div class="text-sm text-gray-400 mt-1 font-medium">{{ $barang['supplier_count'] }} supplier</div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                            <div class="text-sm text-gray-500 font-medium">
                                                💰HPP Rp{{ number_format(($barang['avg_hpp'] ?? 0)/1000, 0) }}k
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
                    @endif

                    <!-- Step 3: Input Jasa -->
                    @if($currentStep == 3)
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Input Jasa Service</h2>
                                    <p class="text-gray-500">Tambahkan jasa yang dikerjakan</p>
                                </div>
                            </div>

                            <!-- Form Input Jasa -->
                            <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl p-6 border border-gray-200">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jasa</label>
                                        <input wire:model="nama_jasa" type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Contoh: Ganti oli, Tune up, dll">
                                        @error('nama_jasa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jasa</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                            <input wire:model="harga_jasa" type="number" min="0" class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="0">
                                        </div>
                                        @error('harga_jasa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                                    <textarea wire:model="keterangan_jasa" rows="2" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Detail pekerjaan yang dikerjakan..."></textarea>
                                </div>
                                <button wire:click="tambahJasa" class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:from-purple-600 hover:to-pink-600 font-semibold transition-all duration-300">
                                    Tambah Jasa
                                </button>
                            </div>

                            <!-- List Jasa -->
                            @if(count($itemsJasa) > 0)
                                <div class="space-y-3">
                                    <h3 class="text-lg font-semibold text-gray-800">Jasa yang Ditambahkan</h3>
                                    @foreach($itemsJasa as $index => $jasa)
                                        <div class="bg-white rounded-xl p-4 border border-gray-200 flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ $jasa['nama_jasa'] }}</h4>
                                                @if($jasa['keterangan'])
                                                    <p class="text-sm text-gray-500 mt-1">{{ $jasa['keterangan'] }}</p>
                                                @endif
                                                <p class="text-lg font-bold text-green-600 mt-2">Rp{{ number_format($jasa['harga_jasa'], 0, ',', '.') }}</p>
                                            </div>
                                            <button wire:click="hapusJasa({{ $index }})" class="text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Diagnosa dan Pekerjaan -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosa</label>
                                    <textarea wire:model="diagnosa" rows="4" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Hasil pemeriksaan dan diagnosa masalah..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan yang Dilakukan</label>
                                    <textarea wire:model="pekerjaan_dilakukan" rows="4" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Detail pekerjaan yang sudah dilakukan..."></textarea>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Step 4: Enhanced Payment System with Fixed Radio Buttons -->
                    @if($currentStep == 4)
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Status Pekerjaan & Pembayaran</h2>
                                    <p class="text-gray-500">Atur status pekerjaan dan strategi pembayaran</p>
                                </div>
                            </div>

                            <!-- Transaction Summary -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Transaksi</h3>
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">Rp{{ number_format($this->total_barang, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-500">Total Barang</div>
                                        <div class="text-xs text-gray-400">{{ count($itemsBarang) }} item</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-purple-600">Rp{{ number_format($this->total_jasa, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-500">Total Jasa</div>
                                        <div class="text-xs text-gray-400">{{ count($itemsJasa) }} jasa</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-green-600">Rp{{ number_format($this->total_keseluruhan, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-500">Total Keseluruhan</div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- Work Status & Payment Strategy -->
                                <div class="space-y-6">
                                    <!-- Work Status with Fixed Radio Buttons -->
                                    <div class="bg-white rounded-2xl p-6 border border-gray-200">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">🔧 Status Pekerjaan</h3>
                                        <div class="space-y-3">
                                            <!-- Belum Dikerjakan -->
                                            <div class="cursor-pointer" wire:click="$set('status_pekerjaan', 'belum_dikerjakan')">
                                                <div class="p-4 border-2 rounded-xl transition-all duration-300 hover:border-orange-300
                                                    {{ $status_pekerjaan == 'belum_dikerjakan' ? 'border-orange-500 bg-orange-50 shadow-md' : 'border-gray-200' }}">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                            {{ $status_pekerjaan == 'belum_dikerjakan' ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                                            @if($status_pekerjaan == 'belum_dikerjakan')
                                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">Belum Dikerjakan</div>
                                                            <div class="text-sm text-gray-500">Mobil masih menunggu antrian</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Sedang Dikerjakan -->
                                            <div class="cursor-pointer" wire:click="$set('status_pekerjaan', 'sedang_dikerjakan')">
                                                <div class="p-4 border-2 rounded-xl transition-all duration-300 hover:border-blue-300
                                                    {{ $status_pekerjaan == 'sedang_dikerjakan' ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-200' }}">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                            {{ $status_pekerjaan == 'sedang_dikerjakan' ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}">
                                                            @if($status_pekerjaan == 'sedang_dikerjakan')
                                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">Sedang Dikerjakan</div>
                                                            <div class="text-sm text-gray-500">Pekerjaan sedang berlangsung</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Selesai -->
                                            <div class="cursor-pointer" wire:click="$set('status_pekerjaan', 'selesai')">
                                                <div class="p-4 border-2 rounded-xl transition-all duration-300 hover:border-green-300
                                                    {{ $status_pekerjaan == 'selesai' ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-200' }}">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                            {{ $status_pekerjaan == 'selesai' ? 'border-green-500 bg-green-500' : 'border-gray-300' }}">
                                                            @if($status_pekerjaan == 'selesai')
                                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-semibold text-gray-900">Selesai</div>
                                                            <div class="text-sm text-gray-500">Pekerjaan sudah selesai</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Strategy with Fixed Radio Buttons -->
                                    <div class="bg-white rounded-2xl p-6 border border-gray-200">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Strategi Pembayaran</h3>
                                        <div class="space-y-3">
                                            <!-- Bayar Setelah Selesai -->
                                            <div class="cursor-pointer" wire:click="$set('strategi_pembayaran', 'bayar_akhir')">
                                                <div class="p-4 border-2 rounded-xl transition-all duration-300 hover:border-green-300
                                                    {{ $strategi_pembayaran == 'bayar_akhir' ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-200' }}">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                                {{ $strategi_pembayaran == 'bayar_akhir' ? 'border-green-500 bg-green-500' : 'border-gray-300' }}">
                                                                @if($strategi_pembayaran == 'bayar_akhir')
                                                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="font-semibold text-gray-900">Bayar Setelah Selesai</div>
                                                                <div class="text-sm text-gray-500">Pembayaran saat mobil sudah selesai</div>
                                                            </div>
                                                        </div>
                                                        <div class="text-2xl">✅</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Bayar Dimuka -->
                                            <div class="cursor-pointer" wire:click="$set('strategi_pembayaran', 'bayar_dimuka')">
                                                <div class="p-4 border-2 rounded-xl transition-all duration-300 hover:border-blue-300
                                                    {{ $strategi_pembayaran == 'bayar_dimuka' ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-200' }}">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                                {{ $strategi_pembayaran == 'bayar_dimuka' ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}">
                                                                @if($strategi_pembayaran == 'bayar_dimuka')
                                                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="font-semibold text-gray-900">Bayar Dimuka</div>
                                                                <div class="text-sm text-gray-500">Bayar penuh saat mulai dikerjakan</div>
                                                            </div>
                                                        </div>
                                                        <div class="text-2xl">💸</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Cicilan Fleksibel -->
                                            <div class="cursor-pointer" wire:click="$set('strategi_pembayaran', 'cicilan')">
                                                <div class="p-4 border-2 rounded-xl transition-all duration-300 hover:border-purple-300
                                                    {{ $strategi_pembayaran == 'cicilan' ? 'border-purple-500 bg-purple-50 shadow-md' : 'border-gray-200' }}">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                                {{ $strategi_pembayaran == 'cicilan' ? 'border-purple-500 bg-purple-500' : 'border-gray-300' }}">
                                                                @if($strategi_pembayaran == 'cicilan')
                                                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="font-semibold text-gray-900">Cicilan Fleksibel</div>
                                                                <div class="text-sm text-gray-500">Bisa bayar kapan saja, cicil atau lunas</div>
                                                            </div>
                                                        </div>
                                                        <div class="text-2xl">📊</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details & Status -->
                                <div class="space-y-6">
                                    <!-- Payment Form -->
                                    <div class="bg-white rounded-2xl p-6 border border-gray-200">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">💳 Detail Pembayaran</h3>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                                <select wire:model="metode_pembayaran" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    <option value="tunai">💵 Tunai</option>
                                                    <option value="transfer">🏦 Transfer</option>
                                                </select>
                                            </div>

                                            <!-- Enhanced Payment Amount Input with Business Logic Display -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Jumlah Dibayar Sekarang
                                                    @php
                                                        $canPay = false;
                                                        $paymentMessage = '';
                                                        
                                                        if ($strategi_pembayaran == 'bayar_akhir') {
                                                            $canPay = $status_pekerjaan == 'selesai';
                                                            $paymentMessage = $canPay ? '(Bisa dibayar - pekerjaan selesai)' : '(Tunggu pekerjaan selesai)';
                                                        } elseif ($strategi_pembayaran == 'bayar_dimuka') {
                                                            $canPay = in_array($status_pekerjaan, ['sedang_dikerjakan', 'selesai']);
                                                            $paymentMessage = $canPay ? '(Bisa dibayar - pekerjaan dimulai)' : '(Tunggu pekerjaan dimulai)';
                                                        } else {
                                                            $canPay = true;
                                                            $paymentMessage = '(Fleksibel - bisa bayar kapan saja)';
                                                        }
                                                    @endphp
                                                    <span class="text-xs {{ $canPay ? 'text-green-600' : 'text-orange-500' }}">{{ $paymentMessage }}</span>
                                                </label>
                                                
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                                                    <input wire:model.live="jumlah_dibayar_sekarang" 
                                                        type="number" min="0" max="{{ $this->total_keseluruhan }}"
                                                        class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 font-semibold text-lg {{ !$canPay && $jumlah_dibayar_sekarang > 0 ? 'border-red-300 bg-red-50' : '' }}" 
                                                        placeholder="0">
                                                </div>
                                                
                                                @error('jumlah_dibayar_sekarang') 
                                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                @enderror
                                                
                                                <!-- Payment Validation Message -->
                                                @if (!$canPay && $jumlah_dibayar_sekarang > 0)
                                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                        <div class="flex items-center space-x-2">
                                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                            </svg>
                                                            <span class="text-sm text-red-700">
                                                                @if($strategi_pembayaran == 'bayar_akhir')
                                                                    Pembayaran hanya bisa dilakukan setelah pekerjaan selesai
                                                                @elseif($strategi_pembayaran == 'bayar_dimuka')
                                                                    Pembayaran hanya bisa dilakukan setelah pekerjaan dimulai
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Quick Payment Buttons -->
                                            @if($canPay && $this->total_keseluruhan > 0)
                                                <div class="grid grid-cols-3 gap-2">
                                                    <button type="button" 
                                                            wire:click="$set('jumlah_dibayar_sekarang', 0)"
                                                            class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                                        Tidak Bayar
                                                    </button>
                                                    <button type="button" 
                                                            wire:click="$set('jumlah_dibayar_sekarang', {{ floor($this->total_keseluruhan / 2) }})"
                                                            class="px-3 py-2 text-sm bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg transition-colors">
                                                        50%
                                                    </button>
                                                    <button type="button" 
                                                            wire:click="$set('jumlah_dibayar_sekarang', {{ $this->total_keseluruhan }})"
                                                            class="px-3 py-2 text-sm bg-green-100 hover:bg-green-200 text-green-800 rounded-lg transition-colors">
                                                        Lunas
                                                    </button>
                                                </div>
                                            @endif

                                            @if($this->status_pembayaran != 'lunas')
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo (untuk sisa)</label>
                                                    <input wire:model="jatuh_tempo" type="date" min="{{ date('Y-m-d') }}" 
                                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                                </div>
                                            @endif

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Surat Pesanan (Opsional)</label>
                                                <input wire:model="no_surat_pesanan" type="text" 
                                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                    placeholder="Nomor surat pesanan">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                                <textarea wire:model="keterangan_pembayaran" rows="2" 
                                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                        placeholder="Keterangan tambahan untuk pembayaran"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enhanced Payment Status Display -->
                                    @php
                                        $statusPembayaran = $this->status_pembayaran;
                                        $sisaPembayaran = $this->sisa_pembayaran;
                                        $totalDibayar = $total_sudah_dibayar + $jumlah_dibayar_sekarang;
                                    @endphp
                                    
                                    <div class="bg-gradient-to-r 
                                        {{ $statusPembayaran == 'lunas' ? 'from-green-50 to-emerald-50 border-green-200' : 
                                        ($statusPembayaran == 'sebagian' ? 'from-yellow-50 to-orange-50 border-yellow-200' : 'from-gray-50 to-slate-50 border-gray-200') }} 
                                        border rounded-2xl p-6">
                                        
                                        <h4 class="font-bold text-xl mb-4
                                            {{ $statusPembayaran == 'lunas' ? 'text-green-800' : 
                                            ($statusPembayaran == 'sebagian' ? 'text-orange-800' : 'text-gray-800') }}">
                                            
                                            @if($statusPembayaran == 'lunas')
                                                ✅ STATUS: LUNAS
                                            @elseif($statusPembayaran == 'sebagian')
                                                ⚠️ STATUS: SEBAGIAN DIBAYAR
                                            @else
                                                @if($strategi_pembayaran == 'bayar_akhir')
                                                    ⏳ STATUS: MENUNGGU SELESAI
                                                @elseif($strategi_pembayaran == 'bayar_dimuka')
                                                    ⏳ STATUS: MENUNGGU MULAI
                                                @else
                                                    📋 STATUS: BELUM DIBAYAR
                                                @endif
                                            @endif
                                        </h4>
                                        
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700 font-medium">Total Tagihan:</span>
                                                <span class="text-xl font-bold text-gray-900">Rp{{ number_format($this->total_keseluruhan, 0, ',', '.') }}</span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700 font-medium">Akan Dibayar Sekarang:</span>
                                                <span class="text-lg font-semibold text-blue-600">Rp{{ number_format($jumlah_dibayar_sekarang, 0, ',', '.') }}</span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                                <span class="text-gray-700 font-medium">
                                                    @if($statusPembayaran == 'lunas')
                                                        Status Pembayaran:
                                                    @else
                                                        Sisa Harus Dibayar:
                                                    @endif
                                                </span>
                                                <span class="text-2xl font-bold 
                                                    {{ $statusPembayaran == 'lunas' ? 'text-green-600' : 'text-orange-600' }}">
                                                    @if($statusPembayaran == 'lunas')
                                                        LUNAS
                                                    @else
                                                        Rp{{ number_format($sisaPembayaran, 0, ',', '.') }}
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            @if($statusPembayaran != 'lunas' && $jatuh_tempo)
                                                <div class="text-center mt-4 p-3 bg-white/50 rounded-lg">
                                                    <span class="text-sm text-gray-600">Jatuh Tempo Sisa: </span>
                                                    <span class="font-semibold text-orange-700">{{ \Carbon\Carbon::parse($jatuh_tempo)->format('d F Y') }}</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Payment Strategy Info -->
                                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                                <div class="text-sm text-blue-700">
                                                    <strong>Strategi:</strong>
                                                    @if($strategi_pembayaran == 'bayar_akhir')
                                                        Bayar setelah pekerjaan selesai
                                                        @if($status_pekerjaan != 'selesai')
                                                            <span class="block text-xs mt-1 text-blue-600">⏳ Menunggu pekerjaan selesai untuk pembayaran</span>
                                                        @else
                                                            <span class="block text-xs mt-1 text-green-600">✅ Pekerjaan selesai, siap untuk dibayar</span>
                                                        @endif
                                                    @elseif($strategi_pembayaran == 'bayar_dimuka')
                                                        Bayar dimuka saat mulai dikerjakan
                                                        @if($status_pekerjaan == 'belum_dikerjakan')
                                                            <span class="block text-xs mt-1 text-blue-600">⏳ Menunggu pekerjaan dimulai untuk pembayaran</span>
                                                        @else
                                                            <span class="block text-xs mt-1 text-green-600">✅ Pekerjaan dimulai, siap untuk dibayar</span>
                                                        @endif
                                                    @else
                                                        Pembayaran fleksibel (cicilan/kapan saja)
                                                        <span class="block text-xs mt-1 text-green-600">✅ Bisa dibayar kapan saja sesuai kebutuhan</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        @if($currentStep > 1)
                            <button wire:click="prevStep" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all duration-300">
                                ← Sebelumnya
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if($currentStep < 4)
                            <button wire:click="nextStep" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 font-semibold transition-all duration-300">
                                Selanjutnya →
                            </button>
                        @else
                            <!-- Enhanced Save Button with Business Logic Validation -->
                            <button 
                                wire:click="simpanTransaksi" 
                                wire:loading.attr="disabled"
                                wire:target="simpanTransaksi"
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 font-bold text-lg transition-all duration-300 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ (empty($itemsBarang) && empty($itemsJasa)) || $isSaving ? 'disabled' : '' }}>
                                
                                <span wire:loading.remove wire:target="simpanTransaksi">
                                    💾 Simpan Transaksi
                                </span>
                                
                                <span wire:loading wire:target="simpanTransaksi" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        @endif
                    </div>

                    @error('general')
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mt-4">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Enhanced Sidebar with Payment Status -->
            <div class="col-span-4">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 sticky top-6">
                    <!-- Summary Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Ringkasan</h3>
                            <p class="text-gray-500">Kasir: {{ $kasir }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Enhanced Status Display -->
                    @if($currentStep == 4)
                        <div class="mb-6 space-y-3">
                            <!-- Work Status -->
                            <div class="bg-gradient-to-r 
                                {{ $status_pekerjaan == 'selesai' ? 'from-green-100 to-emerald-100' : 
                                   ($status_pekerjaan == 'sedang_dikerjakan' ? 'from-blue-100 to-indigo-100' : 'from-orange-100 to-yellow-100') }} 
                                rounded-xl p-3 border">
                                <div class="text-center">
                                    <div class="text-sm font-semibold 
                                        {{ $status_pekerjaan == 'selesai' ? 'text-green-800' : 
                                           ($status_pekerjaan == 'sedang_dikerjakan' ? 'text-blue-800' : 'text-orange-800') }}">
                                        🔧 STATUS PEKERJAAN
                                    </div>
                                    <div class="text-lg font-bold 
                                        {{ $status_pekerjaan == 'selesai' ? 'text-green-600' : 
                                           ($status_pekerjaan == 'sedang_dikerjakan' ? 'text-blue-600' : 'text-orange-600') }}">
                                        {{ strtoupper(str_replace('_', ' ', $status_pekerjaan)) }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Payment Strategy -->
                            <div class="bg-gradient-to-r 
                                {{ $strategi_pembayaran == 'bayar_akhir' ? 'from-green-100 to-teal-100' : 
                                   ($strategi_pembayaran == 'bayar_dimuka' ? 'from-blue-100 to-purple-100' : 'from-purple-100 to-pink-100') }} 
                                rounded-xl p-3 border">
                                <div class="text-center">
                                    <div class="text-sm font-semibold 
                                        {{ $strategi_pembayaran == 'bayar_akhir' ? 'text-green-800' : 
                                           ($strategi_pembayaran == 'bayar_dimuka' ? 'text-blue-800' : 'text-purple-800') }}">
                                        💰 STRATEGI PEMBAYARAN
                                    </div>
                                    <div class="text-lg font-bold 
                                        {{ $strategi_pembayaran == 'bayar_akhir' ? 'text-green-600' : 
                                           ($strategi_pembayaran == 'bayar_dimuka' ? 'text-blue-600' : 'text-purple-600') }}">
                                        @if($strategi_pembayaran == 'bayar_akhir')
                                            BAYAR AKHIR
                                        @elseif($strategi_pembayaran == 'bayar_dimuka')
                                            BAYAR DIMUKA
                                        @else
                                            CICILAN
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-3 border border-blue-100">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600">{{ count($itemsBarang) }}</div>
                                <div class="text-xs text-blue-500">Barang</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-3 border border-purple-100">
                            <div class="text-center">
                                <div class="text-lg font-bold text-purple-600">{{ count($itemsJasa) }}</div>
                                <div class="text-xs text-purple-500">Jasa</div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Summary -->
                    @if($currentStep > 1 && $nama_pelanggan)
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Info Pelanggan</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><strong>{{ $nama_pelanggan }}</strong></div>
                                @if($kontak)<div>📞 {{ $kontak }}</div>@endif
                                @if($nopol)<div>🚗 {{ $nopol }}</div>@endif
                                @if($merk_mobil && $tipe_mobil)<div>{{ $merk_mobil }} {{ $tipe_mobil }}</div>@endif
                            </div>
                        </div>
                    @endif

                    <!-- Items in Cart -->
                    @if(count($itemsBarang) > 0 || count($itemsJasa) > 0)
                        <div class="space-y-4 mb-6 max-h-60 overflow-y-auto custom-scrollbar">
                            <!-- Barang Items -->
                            @if(count($itemsBarang) > 0)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Barang</h4>
                                    @foreach($itemsBarang as $index => $item)
                                        <div class="bg-gray-50 rounded-lg p-3 mb-2 flex justify-between items-center">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900 text-sm">{{ $item['nama'] }}</h5>
                                                <div class="text-xs text-gray-500">{{ $item['jumlah'] }} × Rp{{ number_format($item['harga_jual'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-bold text-green-600">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                                <button wire:click="hapusItemBarang({{ $index }})" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Jasa Items -->
                            @if(count($itemsJasa) > 0)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Jasa</h4>
                                    @foreach($itemsJasa as $index => $jasa)
                                        <div class="bg-purple-50 rounded-lg p-3 mb-2 flex justify-between items-center">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900 text-sm">{{ $jasa['nama_jasa'] }}</h5>
                                                @if($jasa['keterangan'])
                                                    <div class="text-xs text-gray-500">{{ $jasa['keterangan'] }}</div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-bold text-purple-600">Rp{{ number_format($jasa['harga_jasa'], 0, ',', '.') }}</div>
                                                <button wire:click="hapusJasa({{ $index }})" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Enhanced Total Summary -->
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Barang:</span>
                            <span class="font-semibold">Rp{{ number_format($this->total_barang, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Jasa:</span>
                            <span class="font-semibold">Rp{{ number_format($this->total_jasa, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                            <span class="text-gray-900">Total Keseluruhan:</span>
                            <span class="text-green-600">Rp{{ number_format($this->total_keseluruhan, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($currentStep == 4)
                            <div class="text-sm text-gray-600 mt-4 space-y-2 bg-blue-50 rounded-lg p-3">
                                <div class="flex justify-between">
                                    <span>Dibayar Sekarang:</span>
                                    <span class="font-semibold">Rp{{ number_format($jumlah_dibayar_sekarang, 0, ',', '.') }}</span>
                                </div>
                                @php $sisaPembayaran = max(0, $this->total_keseluruhan - $jumlah_dibayar_sekarang); @endphp
                                <div class="flex justify-between font-semibold {{ $sisaPembayaran <= 0 ? 'text-green-600' : 'text-orange-600' }}">
                                    <span>
                                        @if($sisaPembayaran <= 0)
                                            Status:
                                        @else
                                            Sisa:
                                        @endif
                                    </span>
                                    <span>
                                        @if($sisaPembayaran <= 0)
                                            LUNAS
                                        @else
                                            Rp{{ number_format($sisaPembayaran, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Payment Rules Info -->
                                <div class="mt-3 pt-2 border-t border-blue-200">
                                    <div class="text-xs text-blue-700">
                                        @if($strategi_pembayaran == 'bayar_akhir')
                                            @if($status_pekerjaan != 'selesai')
                                                ⏳ Pembayaran setelah pekerjaan selesai
                                            @else
                                                ✅ Pekerjaan selesai, bisa dibayar
                                            @endif
                                        @elseif($strategi_pembayaran == 'bayar_dimuka')
                                            @if($status_pekerjaan == 'belum_dikerjakan')
                                                ⏳ Pembayaran saat mulai dikerjakan
                                            @else
                                                ✅ Pekerjaan dimulai, bisa dibayar
                                            @endif
                                        @else
                                            💳 Bisa bayar kapan saja (fleksibel)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Add Form - Bottom Section (Same as before) -->
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
                                    <input wire:model="harga_jual" type="number" min="0" step="1"
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
                            <button wire:click="tambahItemBarang" 
                                    class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white rounded-2xl hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transition-all duration-200 font-bold text-base shadow-2xl shadow-blue-500/25 transform hover:scale-105">
                                TAMBAH
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <style>
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

        /* Disabled button styles */
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Grid layout fix for form */
        .grid.grid-cols-16 {
            grid-template-columns: repeat(16, 1fr);
        }

        /* Loading animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Enhanced radio button styles */
        input[type="radio"]:checked + div {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
<style>
/* Enhanced radio button styles for better UX */
.cursor-pointer:hover {
    transform: translateY(-1px);
}

.cursor-pointer:active {
    transform: translateY(0);
}

/* Smooth transitions for radio button selections */
.cursor-pointer > div {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom radio button animation */
.cursor-pointer > div.border-orange-500,
.cursor-pointer > div.border-blue-500,
.cursor-pointer > div.border-green-500,
.cursor-pointer > div.border-purple-500 {
    animation: selectPulse 0.4s ease-out;
}

@keyframes selectPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Payment amount input validation styles */
input.border-red-300 {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Quick payment buttons hover effects */
button[wire\:click*="jumlah_dibayar_sekarang"]:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Enhanced focus states */
input:focus, select:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Smooth transitions */
* {
    transition-property: transform, box-shadow, background-color, border-color, color, opacity;
    transition-duration: 0.2s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

</div>

<script>
function selectItem(barangId, namaBarang, stok, avgHPP) {
    if (stok <= 0) {
        showToastMessage('Stok Habis!', 'Item ini sedang tidak tersedia', 'error');
        return;
    }

    @this.call('selectBarang', barangId);

    setTimeout(() => {
        const form = document.getElementById('selectedItemForm');
        if (form) {
            form.classList.remove('hidden');
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

function resetForm() {
    @this.call('resetFormInputs');
    const form = document.getElementById('selectedItemForm');
    if (form) {
        form.classList.add('hidden');
    }
}

// Livewire listeners
document.addEventListener('livewire:init', () => {
    Livewire.on('item-added', () => {
        const form = document.getElementById('selectedItemForm');
        if (form) form.classList.add('hidden');
        showToastMessage('Item Ditambahkan!', 'Berhasil ditambahkan ke keranjang', 'success');
    });

    Livewire.on('form-reset', () => {
        const form = document.getElementById('selectedItemForm');
        if (form) form.classList.add('hidden');
    });

    // Payment validation error
    Livewire.on('payment-validation-error', (data) => {
        const input = document.querySelector('input[wire\\:model\\.live="jumlah_dibayar_sekarang"]');
        if (input) {
            input.classList.add('border-red-300', 'bg-red-50');
            setTimeout(() => {
                input.classList.remove('border-red-300', 'bg-red-50');
            }, 3000);
        }
        showToastMessage('Peringatan Pembayaran', data.message, 'warning');
    });

    // Transaction saving
    Livewire.on('transaction-saving', () => {
        console.log('Transaction save initiated...');
        showToastMessage('Menyimpan...', 'Sedang memproses transaksi service', 'info');
        showLoadingOverlay();
    });

    Livewire.on('transaction-saved', (data) => {
        console.log('Transaction saved successfully:', data);
        hideLoadingOverlay();

        let statusText = '';
        if (data.status_pekerjaan) {
            statusText += ` | Pekerjaan: ${data.status_pekerjaan.toUpperCase().replace('_', ' ')}`;
        }
        if (data.status_pembayaran) {
            statusText += ` | Pembayaran: ${data.status_pembayaran.toUpperCase()}`;
        }
        if (data.strategi_pembayaran) {
            statusText += ` | Strategi: ${data.strategi_pembayaran.toUpperCase().replace('_', ' ')}`;
        }

        showToastMessage('Berhasil!', `Transaksi ${data.invoice} telah disimpan${statusText}`, 'success');

        setTimeout(() => {
            const buttons = document.querySelectorAll('button[data-was-enabled]');
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.removeAttribute('data-was-enabled');
            });
        }, 1000);
    });

    Livewire.on('transaction-error', (error) => {
        console.error('Transaction save error:', error);
        hideLoadingOverlay();
        showToastMessage('Error!', error.message || 'Terjadi kesalahan saat menyimpan', 'error');

        const buttons = document.querySelectorAll('button[data-was-enabled]');
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.removeAttribute('data-was-enabled');
        });
    });
});

function showLoadingOverlay() {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    overlay.innerHTML = `
        <div class="bg-white rounded-2xl p-8 flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-green-500"></div>
            <div class="text-lg font-semibold text-gray-900">Menyimpan Transaksi...</div>
            <div class="text-sm text-gray-500">Mohon tunggu sebentar</div>
        </div>
    `;
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) overlay.remove();
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

    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.transition = 'transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
    }, 100);

    const duration = type === 'error' ? 5000 : type === 'info' ? 2000 : 3500;
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'transform 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    const formVisible = !document.getElementById('selectedItemForm')?.classList.contains('hidden');

    if (e.key === 'Enter' && formVisible) {
        e.preventDefault();
        @this.call('tambahItemBarang');
    }

    if (e.key === 'Escape') {
        const paymentInput = document.querySelector('input[wire\\:model\\.live="jumlah_dibayar_sekarang"]');
        if (paymentInput && document.activeElement === paymentInput) {
            @this.set('jumlah_dibayar_sekarang', 0);
        } else {
            resetForm();
        }
    }

    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const currentStep = @json($currentStep);
        if (currentStep === 4) {
            console.log('Keyboard shortcut: Save transaction');
            @this.call('simpanTransaksi');
        }
    }
});

// Auto uppercase for nopol
document.addEventListener('input', function(e) {
    if (e.target.matches('input[wire\\:model*="nopol"]')) {
        e.target.value = e.target.value.toUpperCase();
    }
});

// console.log('Enhanced payment system with fixed logic loaded');
console.log('Payment strategies: bayar_akhir, bayar_dimuka, cicilan');
console.log('Keyboard shortcuts: Ctrl+S (save), Enter (add item), Escape (cancel/clear)');
</script>
