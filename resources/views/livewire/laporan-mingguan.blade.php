<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 mt-16">
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 max-w-7xl">
        
        <!-- Header Card dengan Month/Year Navigator -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6 mb-4 sm:mb-6 overflow-hidden relative">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-100 to-transparent rounded-full opacity-30 -mr-32 -mt-32"></div>
            
            <div class="relative z-10">
                <!-- Title Section -->
                <div class="mb-4 sm:mb-6">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2 tracking-tight">
                        📊 Laporan Keuangan Mingguan
                    </h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm sm:text-base text-slate-600">
                            Periode: <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($weekStart)->format('d') }}-{{ \Carbon\Carbon::parse($weekEnd)->format('d') }} {{ \Carbon\Carbon::parse($weekEnd)->translatedFormat('F Y') }}</span>
                        </p>
                        @if($isCurrentWeek)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-md animate-pulse">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Live
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Month/Year Selector - Responsive -->
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl p-3 sm:p-4 mb-4 border border-slate-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                        
                        <!-- Left: Month & Year Dropdowns -->
                        <div class="flex items-center gap-2 sm:gap-3 flex-wrap sm:flex-nowrap">
                            <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 shadow-sm border border-slate-300 w-full sm:w-auto">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <select 
                                    wire:model.live="selectedMonth"
                                    class="flex-1 bg-transparent border-0 text-sm sm:text-base font-semibold text-slate-800 focus:outline-none focus:ring-0 cursor-pointer pr-2"
                                >
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            
                            <select 
                                wire:model.live="selectedYear"
                                class="bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm sm:text-base font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm w-full sm:w-auto"
                            >
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Right: Quick Navigation Buttons -->
                        <div class="flex items-center gap-2 justify-between sm:justify-start">
                            <button 
                                wire:click="previousMonth"
                                class="flex items-center justify-center w-10 h-10 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-95"
                                title="Bulan Sebelumnya"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            
                            <button 
                                wire:click="goToCurrentMonth"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-md hover:shadow-lg active:scale-95"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="hidden sm:inline">Bulan Ini</span>
                                <span class="sm:hidden">Hari Ini</span>
                            </button>
                            
                            <button 
                                wire:click="nextMonth"
                                @if($isCurrentMonthSelected) disabled @endif
                                class="flex items-center justify-center w-10 h-10 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:shadow-sm disabled:active:scale-100"
                                title="Bulan Berikutnya"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Week Carousel Navigator - Mobile Optimized -->
                <div class="relative">
                    <!-- Label -->
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Pilih Minggu
                        </h3>
                        <span class="text-xs text-slate-500 hidden sm:inline">
                            Minggu {{ $weekNumber }} dari {{ count($availableWeeks) }}
                        </span>
                    </div>

                    <!-- Week Cards - Horizontal Scroll on Mobile -->
                    <div class="relative group">
                        <!-- Scroll Container -->
                        <div class="overflow-x-auto scrollbar-hide pb-2 -mx-1 px-4 pt-4" id="weekScroll">
                            <div class="flex gap-2 sm:gap-3 min-w-max sm:min-w-0 sm:grid sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                                @foreach($availableWeeks as $week)
                                    <button 
                                        wire:click="selectWeek({{ $week['weekNumber'] }})"
                                        class="relative flex-shrink-0 w-40 sm:w-auto group/card transition-all duration-300 transform hover:scale-105 active:scale-95
                                            {{ $week['weekNumber'] == $currentWeekNumber 
                                                ? 'bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg scale-105' 
                                                : 'bg-white hover:bg-gradient-to-br hover:from-slate-50 hover:to-blue-50 text-slate-700 shadow-md hover:shadow-xl' }}
                                            rounded-xl p-3 sm:p-4 border-2 
                                            {{ $week['weekNumber'] == $currentWeekNumber ? 'border-blue-400' : 'border-slate-200 hover:border-blue-300' }}
                                            {{ $week['isCurrentWeek'] ? 'ring-2 ring-green-400 ring-offset-2' : '' }}"
                                    >
                                        <!-- Current Week Badge -->
                                        @if($week['isCurrentWeek'])
                                            <div class="absolute -top-2 -right-2 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full p-1 shadow-lg animate-bounce">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Week Number -->
                                        <div class="text-center mb-2">
                                            <div class="text-2xl sm:text-3xl font-bold 
                                                {{ $week['weekNumber'] == $currentWeekNumber ? 'text-white' : 'text-blue-600 group-hover/card:text-blue-700' }}">
                                                {{ $week['weekNumber'] }}
                                            </div>
                                            <div class="text-xs font-medium 
                                                {{ $week['weekNumber'] == $currentWeekNumber ? 'text-blue-100' : 'text-slate-500 group-hover/card:text-slate-600' }}">
                                                Minggu
                                            </div>
                                        </div>

                                        <!-- Date Range -->
                                        <div class="text-center pt-2 border-t 
                                            {{ $week['weekNumber'] == $currentWeekNumber ? 'border-blue-400' : 'border-slate-200 group-hover/card:border-blue-200' }}">
                                            <div class="text-xs font-semibold 
                                                {{ $week['weekNumber'] == $currentWeekNumber ? 'text-white' : 'text-slate-600 group-hover/card:text-slate-700' }}">
                                                {{ $week['dateRange'] }}
                                            </div>
                                        </div>

                                        <!-- Selected Indicator -->
                                        @if($week['weekNumber'] == $currentWeekNumber)
                                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 via-orange-400 to-yellow-400 rounded-b-xl"></div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Scroll Indicators (Mobile Only) -->
                        <div class="sm:hidden absolute -bottom-8 left-1/2 transform -translate-x-1/2 flex gap-1">
                            @foreach($availableWeeks as $index => $week)
                                <div class="w-1.5 h-1.5 rounded-full transition-all duration-300
                                    {{ $week['weekNumber'] == $currentWeekNumber ? 'bg-blue-600 w-4' : 'bg-slate-300' }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Week Navigation Arrows (Desktop) -->
                    <div class="hidden lg:flex items-center justify-center gap-4 mt-4">
                        @if($canGoToPrevious)
                            <button 
                                wire:click="previousWeek"
                                class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-slate-700 to-slate-800 hover:from-slate-800 hover:to-slate-900 text-white rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span>Minggu Sebelumnya</span>
                            </button>
                        @endif
                        
                        @if($canGoToNext)
                            <button 
                                wire:click="nextWeek"
                                class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95"
                            >
                                <span>Minggu Berikutnya</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Mobile Week Navigation - Fixed Bottom -->
                    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-slate-200 shadow-2xl z-50 safe-area-pb">
                        <div class="container mx-auto px-3 py-3">
                            <div class="flex items-center gap-2">
                                @if($canGoToPrevious)
                                    <button 
                                        wire:click="previousWeek"
                                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-700 to-slate-800 hover:from-slate-800 hover:to-slate-900 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg active:scale-95"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="text-sm">Sebelumnya</span>
                                    </button>
                                @endif
                                
                                <div class="flex-shrink-0 text-center px-3">
                                    <div class="text-xs text-slate-500 font-medium">Minggu</div>
                                    <div class="text-xl font-bold text-blue-600">{{ $weekNumber }}</div>
                                </div>
                                
                                @if($canGoToNext)
                                    <button 
                                        wire:click="nextWeek"
                                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg active:scale-95"
                                    >
                                        <span class="text-sm">Berikutnya</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6 overflow-hidden">
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                <nav class="flex overflow-x-auto scrollbar-hide" aria-label="Tabs">
                    <button 
                        wire:click="setActiveTab('pendapatan')"
                        class="flex-1 min-w-fit whitespace-nowrap py-4 px-4 sm:px-6 border-b-3 font-semibold text-sm sm:text-base transition-all duration-200
                            {{ $activeTab === 'pendapatan' 
                                ? 'border-blue-600 text-blue-700 bg-blue-50' 
                                : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span>Pendapatan</span>
                        </span>
                    </button>
                    <button 
                        wire:click="setActiveTab('operasional')"
                        class="flex-1 min-w-fit whitespace-nowrap py-4 px-4 sm:px-6 border-b-3 font-semibold text-sm sm:text-base transition-all duration-200
                            {{ $activeTab === 'operasional' 
                                ? 'border-red-600 text-red-700 bg-red-50' 
                                : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Operasional</span>
                        </span>
                    </button>
                    <button 
                        wire:click="setActiveTab('sparepart')"
                        class="flex-1 min-w-fit whitespace-nowrap py-4 px-4 sm:px-6 border-b-3 font-semibold text-sm sm:text-base transition-all duration-200
                            {{ $activeTab === 'sparepart' 
                                ? 'border-orange-600 text-orange-700 bg-orange-50' 
                                : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Sparepart</span>
                        </span>
                    </button>
                </nav>
            </div>

            @if($activeTab === 'pendapatan')
                <div class="p-3 sm:p-6 pb-24 lg:pb-6">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-blue-100 text-xs sm:text-sm font-medium mb-1">Pendapatan Bruto</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($summary['total_pendapatan_bruto'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-emerald-100 text-xs sm:text-sm font-medium mb-1">Modal</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-purple-100 text-xs sm:text-sm font-medium mb-1">Jasa</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($summary['total_jasa'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-yellow-100 text-xs sm:text-sm font-medium mb-1">Piutang</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($summary['total_piutang'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($weeklyData->count() > 0)
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 overflow-hidden shadow-md">
                           <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900">LAPORAN PENDAPATAN</h3>
                                    <p class="text-xs sm:text-sm text-slate-600 mt-1">BENGKEL FIRDAUS JAYA SENTOSA</p>
                                    <p class="text-xs sm:text-sm text-slate-600">
                                        PERIODE {{ \Carbon\Carbon::parse($weekStart)->format('d') }}-{{ \Carbon\Carbon::parse($weekEnd)->format('d') }}
                                        {{ \Carbon\Carbon::parse($weekEnd)->translatedFormat('F Y') }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-slate-600">MINGGU KE-{{ $weekNumber }}</p>
                                </div>

                                <button 
                                    wire:click="exportPendapatan"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 
                                                012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 
                                                01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-slate-100 to-slate-50">
                                        <tr>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">TGL</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">ITEM</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">JUMLAH</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">DISCOUNT</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">MODAL</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">JASA</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">LABA S.PART</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($weeklyData as $index => $item)
                                            <tr class="hover:bg-blue-50 transition-colors duration-150">
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 border-r border-slate-200 font-medium">{{ $index + 1 }}</td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 border-r border-slate-200">{{ $item['tanggal'] }}</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-slate-900 border-r border-slate-200">{{ $item['item'] }}</td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right border-r border-slate-200 font-semibold">
                                                    {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['discount'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['modal'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right border-r border-slate-200">
                                                    {{ number_format($item['jasa'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right font-bold text-green-700">
                                                    {{ number_format($item['laba_spart'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                                        <tr>
                                            <td colspan="3" class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold border-r border-blue-500">
                                                TOTAL PENDAPATAN BRUTO
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_pendapatan_bruto'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_discount'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_modal'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right border-r border-blue-500">
                                                {{ number_format($summary['total_jasa'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right">
                                                {{ number_format($summary['total_laba_spart'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Summary section --}}
                            <div class="px-4 sm:px-6 py-4 sm:py-6 bg-gradient-to-br from-slate-50 via-white to-blue-50 border-t border-slate-200">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
                                    
                                    {{-- Kolom Kiri: Ringkasan Keuangan --}}
                                    <div class="space-y-3 sm:space-y-4">
                                        <div class="flex items-center space-x-2 mb-3 sm:mb-4">
                                            <div class="w-1 h-6 bg-gradient-to-b from-blue-600 to-blue-800 rounded-full"></div>
                                            <h4 class="text-sm sm:text-base font-bold text-slate-800">
                                                RINGKASAN KEUANGAN MINGGU KE-{{ $weekNumber }}
                                            </h4>
                                        </div>
                                        
                                        <div class="space-y-2 sm:space-y-3">
                                            {{-- Pendapatan Bruto --}}
                                            <div class="bg-white border-l-4 border-green-500 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs sm:text-sm font-semibold text-green-700">Pendapatan Bruto</span>
                                                    <span class="text-sm sm:text-base font-bold text-green-800">
                                                        Rp {{ number_format($summary['total_pendapatan_bruto'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Pengeluaran Spare Part --}}
                                            <div class="bg-white border-l-4 border-red-500 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs sm:text-sm font-semibold text-red-700">Pengeluaran Spare Part</span>
                                                    <span class="text-sm sm:text-base font-bold text-red-800">
                                                        Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Total Operasional --}}
                                            <div class="bg-white border-l-4 border-blue-500 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs sm:text-sm font-semibold text-blue-700">Total Operasional</span>
                                                    <span class="text-sm sm:text-base font-bold text-blue-800">
                                                        Rp {{ number_format($summary['operasional'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Detail Piutang --}}
                                            @if(isset($summary['piutang_per_invoice']) && count($summary['piutang_per_invoice']) > 0)
                                            <div class="bg-white border-l-4 border-orange-500 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                                <h5 class="text-xs sm:text-sm font-bold text-orange-700 mb-2 sm:mb-3">Detail Piutang</h5>
                                                <div class="space-y-1 sm:space-y-2">
                                                    @foreach($summary['piutang_per_invoice'] as $piutang)
                                                        <div class="flex justify-between items-center py-1 text-xs">
                                                            <span class="text-slate-600">Invoice {{ $piutang->invoice }}</span>
                                                            <span class="text-orange-800 font-semibold">
                                                                Rp {{ number_format($piutang->total_piutang, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                    <div class="flex justify-between items-center pt-2 mt-2 border-t border-orange-200">
                                                        <span class="text-xs sm:text-sm font-bold text-orange-700">Total Piutang</span>
                                                        <span class="text-xs sm:text-sm font-bold text-orange-800">
                                                            Rp {{ number_format($summary['total_piutang'], 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan: Detail Komponen Pendapatan --}}
                                    <div class="space-y-3 sm:space-y-4">
                                        <div class="flex items-center space-x-2 mb-3 sm:mb-4">
                                            <div class="w-1 h-6 bg-gradient-to-b from-purple-600 to-purple-800 rounded-full"></div>
                                            <h4 class="text-sm sm:text-base font-bold text-slate-800">
                                                DETAIL KOMPONEN PENDAPATAN
                                            </h4>
                                        </div>

                                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                                            <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
                                                {{-- Jasa --}}
                                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Jasa</span>
                                                    <span class="text-xs sm:text-sm font-bold text-slate-900">
                                                        Rp {{ number_format($summary['total_jasa'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                
                                                {{-- Laba Spare Part --}}
                                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Laba Spare Part</span>
                                                    <span class="text-xs sm:text-sm font-bold text-slate-900">
                                                        Rp {{ number_format($summary['total_laba_spart'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                
                                                {{-- Discount --}}
                                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Discount</span>
                                                    <span class="text-xs sm:text-sm font-bold text-red-600">
                                                        -Rp {{ number_format($summary['total_discount'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                
                                                <hr class="border-slate-200 my-2">
                                                
                                                {{-- Detail DP per Invoice --}}
                                                <div class="space-y-2">
                                                    <span class="text-xs sm:text-sm font-semibold text-slate-700 block">Down Payment (DP):</span>
                                                    @if(isset($summary['dp_per_invoice']) && count($summary['dp_per_invoice']) > 0)
                                                        <div class="max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-slate-100">
                                                            @foreach($summary['dp_per_invoice'] as $dp)
                                                                <div class="flex justify-between items-center py-1 ml-2 sm:ml-4">
                                                                    <span class="text-xs text-slate-600">Invoice {{ $dp['invoice'] }}</span>
                                                                    <span class="text-xs font-semibold text-slate-800">
                                                                        Rp {{ number_format($dp['dp'], 0, ',', '.') }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Total DP --}}
                                                    <div class="flex justify-between items-center py-2 border-t-2 border-slate-200 mt-2">
                                                        <span class="text-xs sm:text-sm font-bold text-slate-700">Total DP</span>
                                                        <span class="text-xs sm:text-sm font-bold text-slate-900">
                                                            Rp {{ number_format($summary['total_dp'], 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pendapatan Bersih - Full Width --}}
                                <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t-2 border-slate-300">
                                    <div class="relative overflow-hidden bg-gradient-to-br {{ $summary['pendapatan_bersih'] >= 0 ? 'from-green-50 via-emerald-50 to-green-100' : 'from-red-50 via-pink-50 to-red-100' }} border-2 {{ $summary['pendapatan_bersih'] >= 0 ? 'border-green-300' : 'border-red-300' }} rounded-2xl p-4 sm:p-6 shadow-xl">
                                        <div class="absolute inset-0 bg-gradient-to-r {{ $summary['pendapatan_bersih'] >= 0 ? 'from-green-200/20 to-emerald-200/20' : 'from-red-200/20 to-pink-200/20' }}"></div>
                                        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                            <div class="flex-1">
                                                <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">
                                                    💰 PENDAPATAN BERSIH MINGGU KE-{{ $weekNumber }}
                                                </h3>
                                                <p class="text-xs text-slate-600">
                                                    {{ $summary['pendapatan_bersih'] >= 0 ? '🎉 Keuntungan' : '⚠️ Kerugian' }} periode ini
                                                </p>
                                            </div>
                                            <div class="text-left sm:text-right w-full sm:w-auto">
                                                <div class="text-xl sm:text-2xl lg:text-3xl font-black {{ $summary['pendapatan_bersih'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                                    {{ $summary['pendapatan_bersih'] >= 0 ? '+' : '' }}Rp {{ number_format($summary['pendapatan_bersih'], 0, ',', '.') }}
                                                </div>
                                                <div class="text-xs sm:text-sm {{ $summary['pendapatan_bersih'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold mt-1">
                                                    {{ $summary['pendapatan_bersih'] >= 0 ? '↗' : '↘' }} {{ $summary['pendapatan_bersih'] >= 0 ? 'PROFIT' : 'LOSS' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 px-4 sm:px-6 py-8 sm:py-12 text-center shadow-md">
                            <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-3 sm:mt-4 text-sm sm:text-base font-bold text-slate-900">Tidak ada data pendapatan</h3>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">Tidak ada transaksi service pada minggu ini.</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Tab Operasional --}}
            @if($activeTab === 'operasional')
                <div class="p-3 sm:p-6 pb-24 lg:pb-6">
                    {{-- Operational Summary Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-red-100 text-xs sm:text-sm font-medium mb-1">Total Operasional</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($operationalSummary['total_operasional'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-indigo-100 text-xs sm:text-sm font-medium mb-1">Total Item</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">{{ $operationalSummary['total_items'] }}</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-teal-100 text-xs sm:text-sm font-medium mb-1">Rata-rata/Hari</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($operationalSummary['total_operasional'] / 7, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($operationalData->count() > 0)
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 overflow-hidden shadow-md">
                            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900">PENGELUARAN OPERASIONAL</h3>
                                    <p class="text-xs sm:text-sm text-slate-600 mt-1">BENGKEL FIRDAUS JAYA SENTOSA</p>
                                    <p class="text-xs sm:text-sm text-slate-600">
                                        PERIODE {{ \Carbon\Carbon::parse($weekStart)->format('d') }}-{{ \Carbon\Carbon::parse($weekEnd)->format('d') }}
                                        {{ \Carbon\Carbon::parse($weekEnd)->translatedFormat('F Y') }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-slate-600">MINGGU KE-{{ $weekNumber }}</p>
                                </div>

                                <button 
                                    wire:click="exportOperasional"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 
                                                012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 
                                                01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-slate-100 to-slate-50">
                                        <tr>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">Tanggal</th>  
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">Deskripsi</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200"> 
                                        @foreach($operationalData as $index => $item)
                                            <tr class="hover:bg-red-50 transition-colors duration-150">
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 border-r border-slate-200 font-medium">{{ $index + 1 }}</td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 border-r border-slate-200">{{ $item['tanggal'] }}</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-slate-900 border-r border-slate-200">{{ $item['deskripsi'] }}</td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right font-bold text-red-700">
                                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gradient-to-r from-red-600 to-red-700 text-white">
                                        <tr>
                                            <td colspan="3" class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold border-r border-red-500">
                                                TOTAL PENGELUARAN OPERASIONAL
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right">
                                                Rp {{ number_format($operationalSummary['total_operasional'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 px-4 sm:px-6 py-8 sm:py-12 text-center shadow-md">
                            <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-3 sm:mt-4 text-sm sm:text-base font-bold text-slate-900">Tidak ada data operasional</h3>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">Tidak ada pengeluaran operasional pada minggu ini.</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Tab Sparepart --}}
            @if($activeTab === 'sparepart')
                <div class="p-3 sm:p-6 pb-24 lg:pb-6">
                    {{-- Sparepart Summary Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-orange-100 text-xs sm:text-sm font-medium mb-1">Total Pengeluaran</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ number_format($sparepartSummary['total_pengeluaran'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-cyan-100 text-xs sm:text-sm font-medium mb-1">Total Item</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">{{ $sparepartSummary['total_items'] }}</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H15a2 2 0 012 2v0M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m0 0V6a2 2 0 00-2-2H9.5a2 2 0 00-1.06.293L5.707 6.707A1 1 0 005 7.414V8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-lime-500 to-lime-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-lime-100 text-xs sm:text-sm font-medium mb-1">Rata-rata/Item</p>
                                    <p class="text-lg sm:text-2xl font-bold leading-tight">
                                        Rp {{ $sparepartSummary['total_items'] > 0 ? number_format($sparepartSummary['total_pengeluaran'] / $sparepartSummary['total_items'], 0, ',', '.') : '0' }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($sparepartData->count() > 0)
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 overflow-hidden shadow-md">
                            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900">PENGELUARAN SPAREPART</h3>
                                    <p class="text-xs sm:text-sm text-slate-600 mt-1">BENGKEL FIRDAUS JAYA SENTOSA</p>
                                    <p class="text-xs sm:text-sm text-slate-600">
                                        PERIODE {{ \Carbon\Carbon::parse($weekStart)->format('d') }}-{{ \Carbon\Carbon::parse($weekEnd)->format('d') }}
                                        {{ \Carbon\Carbon::parse($weekEnd)->translatedFormat('F Y') }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-slate-600">MINGGU KE-{{ $weekNumber }}</p>
                                </div>

                                <button 
                                    wire:click="exportSparepart"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 
                                                012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 
                                                01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-slate-100 to-slate-50">
                                        <tr>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">No</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">TGL</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider border-r border-slate-200">ITEM</th>
                                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($sparepartData as $index => $item)
                                            <tr class="hover:bg-orange-50 transition-colors duration-150">
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 border-r border-slate-200 font-medium">{{ $index + 1 }}</td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 border-r border-slate-200">{{ $item['tanggal'] }}</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-slate-900 border-r border-slate-200">{{ $item['item'] }}</td>
                                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-slate-900 text-right font-bold text-orange-700">
                                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gradient-to-r from-orange-600 to-orange-700 text-white">
                                        <tr>
                                            <td colspan="3" class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold border-r border-orange-500">
                                                TOTAL PENGELUARAN SPARE PART
                                            </td>
                                            <td class="px-3 sm:px-4 py-4 text-xs sm:text-sm font-bold text-right">
                                                Rp {{ number_format($sparepartSummary['total_pengeluaran'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 px-4 sm:px-6 py-8 sm:py-12 text-center shadow-md">
                            <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="mt-3 sm:mt-4 text-sm sm:text-base font-bold text-slate-900">Tidak ada data pengeluaran sparepart</h3>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">Tidak ada pengeluaran sparepart pada minggu ini.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <!-- Custom CSS untuk hide scrollbar dan smooth scroll -->
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Safe area for mobile */
    .safe-area-pb {
        padding-bottom: env(safe-area-inset-bottom);
    }

    /* Smooth scroll behavior */
    #weekScroll {
        scroll-behavior: smooth;
    }

    /* Border width utilities */
    .border-b-3 {
        border-bottom-width: 3px;
    }
</style>

<!-- Auto-scroll selected week into view on mobile -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const weekScroll = document.getElementById('weekScroll');
        const selectedWeek = weekScroll?.querySelector('.scale-105');
        
        if (selectedWeek && weekScroll) {
            setTimeout(() => {
                selectedWeek.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'nearest',
                    inline: 'center'
                });
            }, 100);
        }
    });

    // Re-center on Livewire update
    document.addEventListener('livewire:navigated', function() {
        const weekScroll = document.getElementById('weekScroll');
        const selectedWeek = weekScroll?.querySelector('.scale-105');
        
        if (selectedWeek && weekScroll) {
            setTimeout(() => {
                selectedWeek.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'nearest',
                    inline: 'center'
                });
            }, 100);
        }
    });
</script>
</div>

