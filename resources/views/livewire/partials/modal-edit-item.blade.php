 <div>
@if($showEditItemModal && $editingItem)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Edit {{ $editingItemType === 'barang' ? 'Barang' : 'Jasa' }}
                        </h3>
                        <button wire:click="closeDCModal" class="text-gray-400 hover:text-gray-600">
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
                               @if($editingItem && $editingItem->is_manual)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Beli</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                            <input type="number" 
                                                wire:model="editItemHargaBeli"
                                                min="0" 
                                                step="1000"
                                                placeholder="0"
                                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                        </div>
                                        @error('editItemHargaBeli') 
                                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                @endif
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
                        <button wire:click="closeDCModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
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
</div>
