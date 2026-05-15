<div>
@if($showPaymentModal && $selectedTransaction)
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
     x-data x-on:keydown.escape.window="$wire.closeModal()">
    <div class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col max-h-[92vh]">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">Proses pembayaran</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedTransaction->invoice }} · {{ $selectedTransaction->pelangganMobil->nama_pelanggan }}</p>
                </div>
            </div>
            <button wire:click="closeModal"
                    class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable body --}}
        <div class="overflow-y-auto flex-1 p-6 space-y-5">

            {{-- Error --}}
            @if($errors->any())
            <div class="flex gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <ul class="space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Stats row --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total tagihan</p>
                    <p class="text-base font-semibold text-gray-900 dark:text-white">Rp{{ number_format($selectedTransaction->total_keseluruhan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/30 rounded-xl p-4">
                    <p class="text-xs text-green-700 dark:text-green-400 mb-1">Sudah dibayar</p>
                    <p class="text-base font-semibold text-green-700 dark:text-green-400">Rp{{ number_format($selectedTransaction->total_sudah_dibayar, 0, ',', '.') }}</p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900/30 rounded-xl p-4">
                    <p class="text-xs text-orange-700 dark:text-orange-400 mb-1">Sisa tagihan</p>
                    <p class="text-base font-semibold text-orange-700 dark:text-orange-400">Rp{{ number_format($selectedTransaction->sisa_pembayaran, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-3 space-y-2">
                @php $pct = $selectedTransaction->total_keseluruhan > 0 ? round($selectedTransaction->total_sudah_dibayar / $selectedTransaction->total_keseluruhan * 100) : 0; @endphp
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Progres pembayaran</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $pct }}%</span>
                </div>
                <div class="h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-green-600 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 dark:text-gray-500">
                    <span>{{ $selectedTransaction->servicePayments->count() }} pembayaran tercatat</span>
                    <span class="capitalize">{{ $selectedTransaction->status_pembayaran }}</span>
                </div>
            </div>

            {{-- LUNAS state --}}
            @if($selectedTransaction->status_pembayaran === 'lunas')
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-6 text-center space-y-3">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-800 dark:text-green-300">Pembayaran lunas</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">Total dibayar: Rp{{ number_format($selectedTransaction->total_sudah_dibayar, 0, ',', '.') }}</p>
                </div>
                @if($selectedTransaction->servicePayments->count())
                <div class="text-left border-t border-green-200 dark:border-green-700 pt-3 space-y-2">
                    @foreach($selectedTransaction->servicePayments as $pay)
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                            <span>{{ \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d/m/Y') }}</span>
                            <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded">{{ $pay->metode_pembayaran }}</span>
                        </div>
                        <span class="font-medium text-green-700 dark:text-green-400">Rp{{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            @else
            {{-- Form pembayaran --}}
            <div class="space-y-4">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Detail pembayaran</p>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Jumlah bayar</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">Rp</span>
                            <input wire:model.live="jumlahBayar"
                                   type="number" min="1" max="{{ $selectedTransaction->sisa_pembayaran }}"
                                   class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
                                   placeholder="0">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Maks: Rp{{ number_format($selectedTransaction->sisa_pembayaran, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Tanggal bayar</label>
                        <input wire:model="tanggalBayar" type="date"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">
                    </div>
                </div>

                {{-- Metode --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Metode pembayaran</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="$set('metodePembayaran','tunai')"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg border text-left transition-colors
                                    {{ $metodePembayaran === 'tunai'
                                        ? 'border-green-500 bg-green-50 dark:bg-green-900/30'
                                        : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300' }}">
                            <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Tunai</p>
                                <p class="text-xs text-gray-400">Pembayaran cash</p>
                            </div>
                        </button>
                        <button type="button" wire:click="$set('metodePembayaran','transfer')"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg border text-left transition-colors
                                    {{ $metodePembayaran === 'transfer'
                                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30'
                                        : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300' }}">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-800 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Transfer</p>
                                <p class="text-xs text-gray-400">Bank transfer</p>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Upload bukti --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                        Bukti pembayaran
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    @if($buktiPembayaran)
                    <div class="flex items-center justify-between border border-green-300 dark:border-green-600 bg-green-50 dark:bg-green-900/20 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2 text-sm text-green-800 dark:text-green-300 min-w-0">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="truncate">{{ $buktiPembayaran->getClientOriginalName() }}</span>
                            <span class="text-xs text-green-600 shrink-0">{{ number_format($buktiPembayaran->getSize()/1024, 1) }} KB</span>
                        </div>
                        <button wire:click="$set('buktiPembayaran',null)"
                                class="text-xs text-red-500 hover:text-red-700 ml-3 shrink-0">Hapus</button>
                    </div>
                    @else
                    <label for="bukti-bayar" class="flex flex-col items-center justify-center border border-dashed border-gray-300 dark:border-gray-600 rounded-lg py-5 px-4 cursor-pointer hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/10 transition-colors text-center">
                        <svg class="w-7 h-7 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Klik untuk upload file</p>
                        <p class="text-xs text-gray-400 mt-0.5">PNG, JPG, PDF — maks 5 MB</p>
                        <input wire:model="buktiPembayaran" id="bukti-bayar" type="file" accept="image/*,.pdf" class="sr-only">
                    </label>
                    @endif
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                        Keterangan
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea wire:model="keteranganPembayaran" rows="2"
                              class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none resize-none"
                              placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            @endif

        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shrink-0">
            <button wire:click="closeModal"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                {{ $selectedTransaction->status_pembayaran === 'lunas' ? 'Tutup' : 'Batal' }}
            </button>
            @if($selectedTransaction->status_pembayaran !== 'lunas')
            <button wire:click="addPayment"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-green-600 hover:bg-green-700 disabled:opacity-60 text-white rounded-lg transition-colors">
                <svg wire:loading wire:target="addPayment" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <svg wire:loading.remove wire:target="addPayment" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span wire:loading.remove wire:target="addPayment">Proses pembayaran</span>
                <span wire:loading wire:target="addPayment">Memproses...</span>
            </button>
            @endif
        </div>

    </div>
</div>
@endif
</div>