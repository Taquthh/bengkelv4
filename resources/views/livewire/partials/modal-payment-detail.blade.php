<div>
@if($showPaymentDetailModal && $selectedPayment)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $editingPayment ? 'Edit Pembayaran' : 'Detail Pembayaran' }}
                        </h3>
                        <button wire:click="closeDCModal" class="text-gray-400 hover:text-gray-600">
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
                        <button wire:click="closeDCModal"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                            {{ $editingPayment ? 'Batal' : 'Tutup' }}
                        </button>

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
</div>