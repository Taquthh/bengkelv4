<div class="min-h-screen bg-slate-50 mt-16">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6 max-w-7xl">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 mb-2">
                        Laporan Keuangan Bulanan
                    </h1>
                    <p class="text-sm sm:text-base text-slate-600">
                        Periode: 01-{{ \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->format('d') }}
                        {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <button
                        wire:click="previousMonth"
                        class="flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-sm sm:text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Bulan Sebelumnya</span>
                        <span class="sm:hidden">Sebelumnya</span>
                    </button>

                    <button
                        wire:click="nextMonth"
                        class="flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-slate-100 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-200 transition-colors duration-200 font-medium text-sm sm:text-base">
                        <span class="hidden sm:inline">Bulan Berikutnya</span>
                        <span class="sm:hidden">Berikutnya</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-4 sm:mb-6">
            <div class="border-b border-slate-200 overflow-x-auto">
                <nav class="flex px-4 sm:px-6" aria-label="Tabs">
                    <button
                        wire:click="setActiveTab('bulanan')"
                        class="py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-xs sm:text-sm whitespace-nowrap transition-colors duration-200 {{ $activeTab === 'bulanan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        Laporan Bulanan
                    </button>
                    <button
                        wire:click="setActiveTab('piutang')"
                        class="py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-xs sm:text-sm whitespace-nowrap transition-colors duration-200 {{ $activeTab === 'piutang' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        Piutang
                    </button>
                </nav>
            </div>

            @if($activeTab === 'bulanan')
                <div class="p-3 sm:p-6">
                    @if($weeklySummaries->count() > 0)
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div class="w-full sm:w-auto">
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 text-center sm:text-left">
                                        LAPORAN BULANAN — BENGKEL FIRDAUS JAYA SENTOSA
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-600 text-center sm:text-left">
                                        PERIODE 01-{{ \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->format('d') }}
                                        {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                                    </p>
                                </div>
                                
                                <button 
                                    wire:click="exportBulanan"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 
                                                012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 
                                                01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel
                                </button>
                            </div>

                            {{-- Desktop Table View --}}
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Pendapatan per Minggu "FJS"</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Pendapatan Kotor</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Operasional</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Jasa</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Laba Spre Part</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Discount</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">DP</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Piutang</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider">Pendapatan Bersih</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($weeklySummaries as $row)
                                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                                <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">
                                                    * MINGGU {{ $row['week'] }} :
                                                    <span class="text-xs text-slate-500 ml-1">
                                                        ({{ \Carbon\Carbon::parse($row['start'])->format('d M') }} - {{ \Carbon\Carbon::parse($row['end'])->format('d M') }})
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['pendapatan_kotor'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['operasional'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['jasa'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['laba_spart'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['discount'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['dp'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right border-r border-slate-200">{{ number_format($row['piutang'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-sm text-right font-semibold">{{ number_format($row['pendapatan_bersih'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-blue-600 text-white">
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-bold border-r border-blue-500">TOTAL</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_pendapatan_kotor'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_operasional'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_jasa'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_laba_spart'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_discount'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_dp'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-blue-500">{{ number_format($monthlyTotals['total_piutang'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right">{{ number_format($monthlyTotals['total_pendapatan_bersih'], 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Mobile Card View --}}
                            <div class="lg:hidden divide-y divide-slate-200">
                                @foreach($weeklySummaries as $row)
                                    <div class="p-4 hover:bg-slate-50 transition-colors duration-150">
                                        <div class="font-semibold text-slate-900 mb-3 text-sm">
                                            * MINGGU {{ $row['week'] }}
                                            <span class="text-xs text-slate-500 block mt-1">
                                                {{ \Carbon\Carbon::parse($row['start'])->format('d M') }} - {{ \Carbon\Carbon::parse($row['end'])->format('d M') }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">Pendapatan Kotor</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['pendapatan_kotor'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">Operasional</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['operasional'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">Jasa</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['jasa'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">Laba Spart</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['laba_spart'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">Discount</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['discount'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">DP</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['dp'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-slate-50 p-2 rounded">
                                                <div class="text-slate-600">Piutang</div>
                                                <div class="font-semibold text-slate-900">Rp {{ number_format($row['piutang'], 0, ',', '.') }}</div>
                                            </div>
                                            <div class="bg-blue-100 p-2 rounded border border-blue-300">
                                                <div class="text-blue-700 font-semibold">Pendapatan Bersih</div>
                                                <div class="font-bold text-blue-900">Rp {{ number_format($row['pendapatan_bersih'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{-- Mobile Total --}}
                                <div class="bg-blue-600 text-white p-4">
                                    <div class="font-bold text-base mb-3">TOTAL</div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <div class="text-blue-100">Pendapatan Kotor</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_pendapatan_kotor'], 0, ',', '.') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-blue-100">Operasional</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_operasional'], 0, ',', '.') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-blue-100">Jasa</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_jasa'], 0, ',', '.') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-blue-100">Laba Spart</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_laba_spart'], 0, ',', '.') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-blue-100">Discount</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_discount'], 0, ',', '.') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-blue-100">DP</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_dp'], 0, ',', '.') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-blue-100">Piutang</div>
                                            <div class="font-semibold">Rp {{ number_format($monthlyTotals['total_piutang'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="bg-white/20 p-2 rounded">
                                            <div class="text-blue-100 font-semibold">Pendapatan Bersih</div>
                                            <div class="font-bold text-lg">Rp {{ number_format($monthlyTotals['total_pendapatan_bersih'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom summary --}}
                            <div class="px-4 sm:px-6 py-4 sm:py-6 bg-slate-50 border-t border-slate-200">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                                    <div class="space-y-2">
                                        <p class="text-xs sm:text-sm text-slate-700 flex justify-between">
                                            <span class="font-semibold">TOTAL PENDAPATAN KOTOR {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</span>
                                            <span>Rp {{ number_format($monthlyTotals['total_pendapatan_kotor'], 0, ',', '.') }}</span>
                                        </p>
                                        <p class="text-xs sm:text-sm text-slate-700 flex justify-between">
                                            <span class="font-semibold">TOTAL PENGELUARAN {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</span>
                                            <span>Rp {{ number_format($monthlyTotals['total_operasional'], 0, ',', '.') }}</span>
                                        </p>
                                        <p class="text-xs sm:text-sm text-slate-700 flex justify-between">
                                            <span class="font-semibold">TOTAL PIUTANG - DP</span>
                                            <span>Rp {{ number_format($monthlyTotals['total_piutang_dp'], 0, ',', '.') }}</span>
                                        </p>
                                        <hr class="my-2">
                                        <p class="text-xs sm:text-sm font-bold text-slate-900 flex justify-between">
                                            <span>TOTAL PENDAPATAN</span>
                                            <span>Rp {{ number_format($monthlyTotals['total_pendapatan_bersih'], 0, ',', '.') }}</span>
                                        </p>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="border-2 border-slate-300 rounded-xl p-3 sm:p-4">
                                            <p class="text-xs sm:text-sm font-semibold text-slate-700 flex justify-between items-center">
                                                <span>PENDAPATAN BERSIH BENGKEL "FJS"</span>
                                                <span class="text-slate-900">Rp {{ number_format($monthlyTotals['total_pendapatan_bersih'], 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                        <div class="border-2 border-slate-300 rounded-xl p-3 sm:p-4">
                                            <p class="text-xs sm:text-sm font-semibold text-slate-700 flex justify-between items-center">
                                                <span>PENDAPATAN BERSIH {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</span>
                                                <span class="text-slate-900">Rp {{ number_format($monthlyTotals['total_pendapatan_bersih'], 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-slate-200 px-4 sm:px-6 py-8 sm:py-12 text-center">
                            <svg class="mx-auto h-10 sm:h-12 w-10 sm:w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada data untuk bulan ini</h3>
                            <p class="mt-1 text-xs sm:text-sm text-slate-500">Belum ada transaksi pada periode ini.</p>
                        </div>
                    @endif
                </div>
            @endif

            @if($activeTab === 'piutang')
                <div class="p-3 sm:p-6">
                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="w-full sm:w-auto">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-900 text-center sm:text-left">
                                    PIUTANG — PERIODE {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 text-center sm:text-left">
                                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                                </p>
                            </div>
                            
                            @if($piutangDetail->count() > 0)
                                <button 
                                    wire:click="exportPiutang"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 
                                                012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 
                                                01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel
                                </button>
                            @endif
                        </div>

                        {{-- Desktop Table View --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">No.Invoice</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Merk/No.Pol</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Laporan</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wider border-r border-slate-200">Tagihan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @forelse($piutangDetail as $row)
                                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $row['no'] }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $row['tanggal'] }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $row['invoice'] }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $row['merk_nopol'] }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-900 border-r border-slate-200">{{ $row['laporan'] }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-slate-900 border-r border-slate-200">Rp {{ number_format($row['tagihan'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-900">
                                                @if($row['lunas'])
                                                    LUNAS - {{ \Carbon\Carbon::parse($row['tanggal_lunas'])->format('d M Y') }}
                                                @else
                                                    {{ $row['keterangan'] }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                                                Tidak ada piutang pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($piutangDetail->count() > 0)
                                    <tfoot class="bg-yellow-600 text-white">
                                        <tr>
                                            <td colspan="5" class="px-4 py-4 text-sm font-bold border-r border-yellow-500">TOTAL</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right border-r border-yellow-500">
                                                Rp {{ number_format($piutangDetail->sum('tagihan'), 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold">
                                                SISA TAGIHAN Rp {{ number_format($piutangDetail->sum('sisa'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>

                        {{-- Mobile Card View --}}
                        <div class="md:hidden divide-y divide-slate-200">
                            @forelse($piutangDetail as $row)
                                <div class="p-4 hover:bg-slate-50 transition-colors duration-150">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs font-semibold text-slate-500">#{$row['no']}</span>
                                        <span class="text-xs text-slate-600">{{ $row['tanggal'] }}</span>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm">
                                        <div>
                                            <div class="text-xs text-slate-500">Invoice</div>
                                            <div class="font-medium text-slate-900">{{ $row['invoice'] }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="text-xs text-slate-500">Merk/No.Pol</div>
                                            <div class="font-medium text-slate-900">{{ $row['merk_nopol'] }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="text-xs text-slate-500">Laporan</div>
                                            <div class="font-medium text-slate-900">{{ $row['laporan'] }}</div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                            <div class="text-xs text-slate-500">Tagihan</div>
                                            <div class="font-bold text-yellow-600">Rp {{ number_format($row['tagihan'], 0, ',', '.') }}</div>
                                        </div>
                                        
                                        <div class="bg-slate-100 p-2 rounded">
                                            <div class="text-xs text-slate-600">
                                                @if($row['lunas'])
                                                    <span class="text-green-600 font-semibold">LUNAS</span> - {{ \Carbon\Carbon::parse($row['tanggal_lunas'])->format('d M Y') }}
                                                @else
                                                    {{ $row['keterangan'] }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-sm text-slate-500">
                                    Tidak ada piutang pada periode ini.
                                </div>
                            @endforelse
                            
                            @if($piutangDetail->count() > 0)
                                <div class="bg-yellow-600 text-white p-4">
                                    <div class="font-bold text-base mb-3">TOTAL</div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Total Tagihan</span>
                                            <span class="font-bold">Rp {{ number_format($piutangDetail->sum('tagihan'), 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between pt-2 border-t border-yellow-500">
                                            <span>Sisa Tagihan</span>
                                            <span class="font-bold">Rp {{ number_format($piutangDetail->sum('sisa'), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>