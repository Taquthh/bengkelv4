<div>
@if($showDeleteItemConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-red-600">Konfirmasi Hapus Item</h3>
                        <button wire:click="closeDCModal" class="text-gray-400 hover:text-gray-600">
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
                        <button wire:click="closeDCModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
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
</div>
