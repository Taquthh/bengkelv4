<div class="min-h-screen bg-slate-50 mt-16">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 mb-2">
                        Laporan Keuangan Mingguan
                    </h1>
                    <p class="text-slate-600">
                        Periode: {{ \Carbon\Carbon::parse($weekStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}
                        @if($isCurrentWeek)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                Minggu Ini
                            </span>
                        @endif
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    @if($canGoToPrevious)
                        <button 
                            wire:click="previousWeek"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Minggu Sebelumnya
                        </button>
                    @endif
                    
                    @if($canGoToNext)
                        <button 
                            wire:click="nextWeek"
                            class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-200 transition-colors duration-200 font-medium"
                        >
                            Minggu Berikutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
            <div class="border-b border-slate-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button 
                        wire:click="setActiveTab('pendapatan')"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'pendapatan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
                    >
                        Laporan Pendapatan
                    </button>
                    <button 
                        wire:click="setActiveTab('operasional')"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'operasional' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
                    >
                        Pengeluaran Operasional
                    </button>
                    <button 
                        wire:click="setActiveTab('sparepart')"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'sparepart' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
                    >
                        Pengeluaran Sparepart
                    </button>
                </nav>
            </div>

            @if($activeTab === 'pendapatan')
                <div class="p-6">
                    {{-- Summary cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Pendapatan Bruto</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($summary['total_pendapatan_bruto'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-100 text-sm font-medium">Discount</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($summary['total_discount'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-100 text-sm font-medium">Modal</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Jasa</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($summary['total_jasa'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium">Piutang</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($summary['total_piutang'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($weeklyData->count() > 0)
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                                <h3 class="text-lg font-semibold text-slate-900">LAPORAN PENDAPATAN</h3>
                                <p class="text-sm text-slate-600 mt-1">BENGKEL FIRDAUS JAYA SENTOSA</p>
                                <p class="text-sm text-slate-600">PERIODE {{ \Carbon\Carbon::parse($weekStart)->format('d-m') }} {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}</p>
                                <p class="text-sm text-slate-600">MINGGU KE-{{ $weekNumber }}</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">TGL</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">ITEM</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">JUMLAH</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">DISCOUNT</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">MODAL</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">JASA</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider">LABA S.PART</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($weeklyData as $index => $item)
                                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 border-r border-slate-200">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 border-r border-slate-200">{{ $item['tanggal'] }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $item['item'] }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['discount'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['modal'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['jasa'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right font-medium">
                                                    {{ number_format($item['laba_spart'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-blue-600 text-white">
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-sm font-bold border-r border-blue-500">
                                                TOTAL PENDAPATAN BRUTO
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_pendapatan_bruto'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_discount'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_modal'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_jasa'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right">
                                                {{ number_format($summary['total_laba_spart'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Summary section --}}
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">PENDAPATAN MINGGU KE - {{ $weekNumber }}</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_pendapatan_bruto'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">PENGELUARAN S.PART MINGGU KE - {{ $weekNumber }}</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_modal'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">PIUTANG INV</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_piutang'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">OPERASIONAL MINGGU KE - {{ $weekNumber }}</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['operasional'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between border-t border-slate-300 pt-2">
                                            <span class="text-sm font-bold text-slate-900">PENDAPATAN BERSIH MINGGU KE - {{ $weekNumber }}</span>
                                            <span class="text-sm font-bold text-slate-900">{{ number_format($summary['pendapatan_bersih'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">JASA</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_jasa'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">Laba S.PART</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_laba_spart'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">DISCOUNT</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_discount'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-700">DP</span>
                                            <span class="text-sm text-slate-900">{{ number_format($summary['total_dp'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between border-t border-slate-300 pt-2">
                                            <span class="text-sm font-bold text-slate-900">TOTAL OPERASIONAL</span>
                                            <span class="text-sm font-bold text-slate-900">{{ number_format($summary['operasional'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-bold text-slate-900">BERSIH MINGGU KE - {{ $weekNumber }}</span>
                                            <span class="text-sm font-bold text-slate-900">{{ number_format($summary['pendapatan_bersih'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-slate-200 px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada data pendapatan</h3>
                            <p class="mt-1 text-sm text-slate-500">Tidak ada transaksi service pada minggu ini.</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Tab Operasional --}}
            @if($activeTab === 'operasional')
                <div class="p-6">
                    {{-- Operational Summary Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-100 text-sm font-medium">Total Operasional</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($operationalSummary['total_operasional'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-100 text-sm font-medium">Total Transaksi</p>
                                    <p class="text-2xl font-bold">{{ $operationalSummary['total_items'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-teal-100 text-sm font-medium">Rata-rata/Hari</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($operationalSummary['total_operasional'] / 7, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($operationalData->count() > 0)
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                                <h3 class="text-lg font-semibold text-slate-900">PENGELUARAN OPERASIONAL</h3>
                                <p class="text-sm text-slate-600 mt-1">Periode {{ \Carbon\Carbon::parse($weekStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Kategori</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Deskripsi</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($operationalData as $index => $item)
                                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 border-r border-slate-200">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 border-r border-slate-200">{{ $item['tanggal'] }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $item['kategori'] }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $item['deskripsi'] }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right font-medium">
                                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-red-600 text-white">
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-sm font-bold border-r border-red-500">
                                                TOTAL PENGELUARAN OPERASIONAL
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right">
                                                Rp {{ number_format($operationalSummary['total_operasional'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-slate-200 px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada data operasional</h3>
                            <p class="mt-1 text-sm text-slate-500">Tidak ada pengeluaran operasional pada minggu ini.</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Tab Sparepart --}}
            @if($activeTab === 'sparepart')
                <div class="p-6">
                    {{-- Sparepart Summary Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-100 text-sm font-medium">Total Pengeluaran</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ number_format($sparepartSummary['total_pengeluaran'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-cyan-100 text-sm font-medium">Total Item</p>
                                    <p class="text-2xl font-bold">{{ $sparepartSummary['total_items'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H15a2 2 0 012 2v0M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m0 0V6a2 2 0 00-2-2H9.5a2 2 0 00-1.06.293L5.707 6.707A1 1 0 005 7.414V8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-lime-500 to-lime-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lime-100 text-sm font-medium">Rata-rata/Item</p>
                                    <p class="text-2xl font-bold">
                                        Rp {{ $sparepartSummary['total_items'] > 0 ? number_format($sparepartSummary['total_pengeluaran'] / $sparepartSummary['total_items'], 0, ',', '.') : '0' }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($sparepartData->count() > 0)
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                                <h3 class="text-lg font-semibold text-slate-900">LAPORAN PENGELUARAN SPARE PART</h3>
                                <p class="text-sm text-slate-600 mt-1">BENGKEL FIRDAUS JAYA SENTOSA</p>
                                <p class="text-sm text-slate-600">PERIODE {{ \Carbon\Carbon::parse($weekStart)->format('d-m') }} {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}</p>
                                <p class="text-sm text-slate-600">MINGGU KE-{{ $weekNumber }}</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">TGL</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">ITEM</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($sparepartData as $index => $item)
                                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 border-r border-slate-200">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 border-r border-slate-200">{{ $item['tanggal'] }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $item['item'] }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 text-right font-medium">
                                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-orange-600 text-white">
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-sm font-bold border-r border-orange-500">
                                                TOTAL PENGELUARAN SPARE PART
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-right">
                                                Rp {{ number_format($sparepartSummary['total_pengeluaran'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-slate-200 px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada data pengeluaran sparepart</h3>
                            <p class="mt-1 text-sm text-slate-500">Tidak ada pengeluaran sparepart pada minggu ini.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>