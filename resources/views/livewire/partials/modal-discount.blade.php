<div>
@if($showDiscountModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Diskon</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Discount Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon</label>
                        <select wire:model="editTipeDiskon" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="nominal">Nominal (Rp)</option>
                            <option value="persentase">Persentase (%)</option>
                        </select>
                    </div>

                    <!-- Discount Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Diskon {{ $editTipeDiskon === 'persentase' ? '(%)' : '(Rp)' }}
                        </label>
                        <input type="number" 
                               wire:model="editDiskon"
                               min="0"
                               {{ $editTipeDiskon === 'persentase' ? 'max="100"' : '' }}
                               step="{{ $editTipeDiskon === 'persentase' ? '0.01' : '1000' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0">
                        @error('editDiskon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Preview -->
                    @if($selectedTransaction && $editDiskon > 0)
                        @php
                            $subtotal = $selectedTransaction->total_barang + $selectedTransaction->total_jasa;
                            $previewDiskonAmount = $editTipeDiskon === 'persentase' ? ($subtotal * $editDiskon / 100) : $editDiskon;
                            $previewTotal = $subtotal - $previewDiskonAmount;
                        @endphp
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-sm text-blue-800">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-red-600">
                                    <span>Diskon:</span>
                                    <span>-Rp{{ number_format($previewDiskonAmount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between font-bold border-t border-blue-200 pt-2 mt-2">
                                    <span>Total Akhir:</span>
                                    <span>Rp{{ number_format($previewTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 px-6 py-4 flex justify-end space-x-3">
                    <button wire:click="closeDiscountModal" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="updateDiscount" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Diskon
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
