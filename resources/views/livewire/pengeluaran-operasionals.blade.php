<div class="min-h-screen bg-slate-50 mt-16">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-slate-900 mb-3">Pengeluaran Operasional</h1>
            <p class="text-slate-600 text-lg">Kelola dan pantau pengeluaran operasional perusahaan per minggu</p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-slate-900 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="bg-emerald-500 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-white">Pengeluaran Minggu Ini</h3>
                            <p class="text-3xl font-bold text-white">Rp {{ number_format($totalMingguIni, 0, ',', '.') }}</p>
                            <p class="text-white/80 text-sm">{{ \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('d M') }} - {{ \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(5)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-white">Total Keseluruhan</h3>
                            <p class="text-3xl font-bold text-white">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                            <p class="text-white/80 text-sm">{{ $daftarPengeluaran->count() }} total transaksi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($isCurrentWeek)
        <div class="mb-8 text-center">
            <button wire:click="openAddModal" 
                class="inline-flex items-center px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-lg rounded-2xl shadow-xl hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pengeluaran Baru
            </button>
        </div>
        @else
        <div class="bg-slate-100 border border-slate-200 rounded-2xl p-6 mb-8 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-slate-900">Mode Lihat Minggu Sebelumnya</h3>
                    <p class="text-slate-600">Anda sedang melihat data minggu sebelumnya. Penambahan data hanya bisa dilakukan di minggu ini.</p>
                    <button wire:click="goToCurrentWeek" class="mt-3 inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali ke Minggu Ini
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden mb-8">
            <div class="bg-blue-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <h3 class="text-xl font-bold text-white">
                                @if($isCurrentWeek)
                                    Minggu Ini
                                @else
                                    {{ $currentWeekOffset }} Minggu yang Lalu
                                @endif
                            </h3>
                            <p class="text-sm text-white/80">{{ $currentWeek['monday']->translatedFormat('d F') }} - {{ $currentWeek['saturday']->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if($canGoToPrevious)
                        <button wire:click="goToPreviousWeek" 
                            class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg border border-white/20 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Minggu Sebelumnya
                        </button>
                        @endif
                        
                        @if(!$isCurrentWeek)
                        <button wire:click="goToNextWeek" 
                            class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg border border-white/20 transition-all duration-200">
                            Minggu Berikutnya
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <button wire:click="goToCurrentWeek" 
                            class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white font-semibold rounded-lg shadow-lg hover:bg-emerald-600 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Minggu Ini
                        </button>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-white/20">
                    <div class="flex items-center justify-between">
                        <span class="text-white/80">Total Pengeluaran:</span>
                        <span class="text-2xl font-bold text-white">Rp {{ number_format($totalWeekInView, 0, ',', '.') }}</span>
                        <span class="text-white/80">{{ $daftarPengeluaran->count() }} transaksi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @if($daftarPengeluaran->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Detail Pengeluaran
                            </h3>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">No</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Nama Item</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Jumlah</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach ($daftarPengeluaran as $index => $pengeluaran)
                                        <tr class="hover:bg-slate-50 transition-all duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-center w-8 h-8 bg-emerald-100 rounded-full">
                                                    <span class="text-sm font-bold text-emerald-600">{{ $index + 1 }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-slate-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-bold text-slate-900">{{ $pengeluaran->tanggal->translatedFormat('l') }}</div>
                                                        <div class="text-xs text-slate-500">{{ $pengeluaran->tanggal->format('d-m-Y') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-slate-900">{{ $pengeluaran->nama_item }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    Rp {{ number_format($pengeluaran->jumlah_pengeluaran, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button wire:click="edit({{ $pengeluaran->id }})"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <button wire:click="confirmDelete({{ $pengeluaran->id }}, '{{ $pengeluaran->nama_item }}')"
                                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-blue-600">
                                        <td colspan="3" class="px-6 py-4 text-right font-bold text-white">
                                            <div class="flex items-center justify-end">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                TOTAL {{ $isCurrentWeek ? 'MINGGU INI' : 'MINGGU' }}
                                            </div>
                                        </td>
                                        <td colspan="2" class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-4 py-2 bg-emerald-500 rounded-lg">
                                                <span class="text-lg font-bold text-white">
                                                    Rp {{ number_format($totalWeekInView, 0, ',', '.') }}
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m0 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">
                            @if($isCurrentWeek)
                                Belum ada data pengeluaran minggu ini
                            @else
                                Tidak ada data pengeluaran untuk minggu ini
                            @endif
                        </h3>
                        <p class="text-slate-600">
                            @if($isCurrentWeek)
                                Klik tombol "Tambah Pengeluaran Baru" untuk menambahkan data pertama
                            @else
                                Tidak ada transaksi yang tercatat pada minggu {{ $currentWeek['monday']->translatedFormat('d F Y') }}
                            @endif
                        </p>
                        @if(!$isCurrentWeek)
                        <button wire:click="goToCurrentWeek" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Kembali ke Minggu Ini
                        </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showAddModal && $isCurrentWeek)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-300">

                <div class="bg-emerald-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Pengeluaran
                        </h3>
                        <button wire:click="closeAddModal" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-1 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="simpan" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                            <input type="date" wire:model="tanggal" 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            @error('tanggal') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Item</label>
                            <input type="text" wire:model="nama_item" placeholder="Masukkan nama item"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            @error('nama_item') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Pengeluaran</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                <input type="number" wire:model="jumlah_pengeluaran" placeholder="0" min="1"
                                    class="w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                            @error('jumlah_pengeluaran') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-200">
                            <button type="button" wire:click="closeAddModal"
                                class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-300">
                
                {{-- Modal Header --}}
                <div class="bg-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Pengeluaran
                        </h3>
                        <button wire:click="closeModal" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-1 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="update" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                            <input type="date" wire:model="tanggal" 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('tanggal') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Item</label>
                            <input type="text" wire:model="nama_item" 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('nama_item') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Pengeluaran</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                <input type="number" wire:model="jumlah_pengeluaran" min="1"
                                    class="w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            @error('jumlah_pengeluaran') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-200">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus -->
    @if($showDeleteModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-[60] p-4">
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-300">
                <div class="p-6 text-center">
                    <!-- Icon Peringatan -->
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Konfirmasi Hapus</h3>
                    <p class="text-slate-600 mb-6">
                        Apakah Anda yakin ingin menghapus item <span class="font-bold text-slate-900">"{{ $deleteItemName }}"</span>? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex flex-col space-y-2">
                        <button wire:click="delete"
                            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg transition-all transform active:scale-95">
                            Ya, Hapus Sekarang
                        </button>
                        <button wire:click="closeDeleteModal"
                            class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success notification after 5 seconds
            const successNotification = document.querySelector('.bg-white.border.border-slate-200');
            if (successNotification && successNotification.textContent.includes('berhasil')) {
                setTimeout(() => {
                    successNotification.style.transition = 'opacity 0.5s ease-out';
                    successNotification.style.opacity = '0';
                    setTimeout(() => {
                        successNotification.style.display = 'none';
                    }, 500);
                }, 5000);
            }
        });
    </script>
    @endpush
</div>
