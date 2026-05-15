<div>
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
</div>
