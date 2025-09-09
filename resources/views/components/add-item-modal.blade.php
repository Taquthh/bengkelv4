<div class="space-y-6">
    <!-- Info Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-semibold text-blue-900">Barang dari Stok Inventory</h4>
                <p class="text-sm text-blue-700 mt-1">Pilih barang yang tersedia di inventory untuk ditambahkan ke transaksi ini.</p>
            </div>
        </div>
    </div>

    <!-- Add Form -->
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <form wire:submit.prevent="addBarangToList" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang</label>
                    <select wire:model.live="selectedBarangId" 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
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
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                    <input wire:model="itemJumlah" 
                           type="number" 
                           min="1" 
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" 
                           placeholder="1">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                        <input wire:model="itemHargaJual" 
                               type="number" 
                               min="0" 
                               step="1000" 
                               class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" 
                               placeholder="0">
                    </div>
                    @if($suggestedPrice > 0)
                        <button type="button" 
                                wire:click="$set('itemHargaJual', {{ $suggestedPrice }})" 
                                class="mt-2 text-xs text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-full transition-colors">
                            Gunakan saran: Rp{{ number_format($suggestedPrice, 0, ',', '.') }}
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="flex justify-end pt-2">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Tambah ke Daftar</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Selected Items List -->
    @if(count($selectedBarangItems) > 0)
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h5 class="font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Barang Terpilih ({{ count($selectedBarangItems) }})
            </h5>
            
            <div class="space-y-3">
                @foreach($selectedBarangItems as $index => $item)
                    <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $item['nama'] }}</div>
                                @if($item['merk']) 
                                    <div class="text-sm text-gray-600">{{ $item['merk'] }}</div>
                                @endif
                                <div class="text-sm text-blue-600 font-medium">
                                    {{ $item['jumlah'] }} × Rp{{ number_format($item['harga_jual'], 0, ',', '.') }} = 
                                    <span class="font-bold">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <button wire:click="removeBarangFromList({{ $index }})" 
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
