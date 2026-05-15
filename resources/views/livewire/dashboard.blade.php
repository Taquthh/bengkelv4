<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Flash Messages ── --}}
            @if (session()->has('message'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('message') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ── Welcome Banner ── --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 text-white shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h1>
                        <p class="text-blue-100 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Role: {{ ucfirst(Auth::user()->role) }} — FJS Auto Service
                        </p>
                    </div>
                    <svg class="w-14 h-14 text-blue-200 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 .001M13 16l2-4h3l2 4M13 16H9m4 0h2"/>
                    </svg>
                </div>
            </div>

            {{-- ── Stats Cards ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Total Transaksi Bulan Ini --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Transaksi Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($dashboardStats['total']) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Belum Lunas --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Belum Lunas</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-1">{{ number_format($dashboardStats['belum_lunas']) }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pekerjaan Selesai --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pekerjaan Selesai</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($dashboardStats['selesai']) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::user()->role === 'owner')
                <div class="space-y-4">

                    {{-- ── KPI Metrics Row ── --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        {{-- Pendapatan bulan ini --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Pendapatan bulan ini</span>
                            </div>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                Rp{{ number_format($ownerStats['pendapatan_bulan_ini'], 0, ',', '.') }}
                            </p>
                            @if($ownerStats['pct_pendapatan'] >= 0)
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                +{{ $ownerStats['pct_pendapatan'] }}% vs bulan lalu
                            </p>
                            @else
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                                {{ $ownerStats['pct_pendapatan'] }}% vs bulan lalu
                            </p>
                            @endif
                        </div>

                        {{-- Rata-rata per order --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Rata-rata per order</span>
                            </div>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                Rp{{ number_format($ownerStats['avg_per_order'], 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">dari {{ $ownerStats['total_transaksi_bulan'] }} transaksi bulan ini</p>
                        </div>

                        {{-- Total piutang --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-700 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Total piutang</span>
                            </div>
                            <p class="text-2xl font-semibold text-orange-600 dark:text-orange-400">
                                Rp{{ number_format($ownerStats['total_piutang'], 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $ownerStats['count_belum_lunas'] }} transaksi belum lunas</p>
                        </div>

                        {{-- Pengeluaran operasional --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Pengeluaran bulan ini</span>
                            </div>
                            <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                                Rp{{ number_format($ownerStats['pengeluaran_bulan_ini'], 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Laba bersih:
                                <span class="font-medium {{ ($ownerStats['pendapatan_bulan_ini'] - $ownerStats['pengeluaran_bulan_ini']) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600' }}">
                                    Rp{{ number_format($ownerStats['pendapatan_bulan_ini'] - $ownerStats['pengeluaran_bulan_ini'], 0, ',', '.') }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- ── Charts Row ── --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                        {{-- Grafik pendapatan 7 hari --}}
                        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pendapatan 7 hari terakhir</h3>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-blue-600 inline-block"></span>Servis</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-green-600 inline-block"></span>Barang</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Nilai dalam rupiah</p>
                            <div class="relative h-48">
                                <canvas id="chartPendapatan7Hari"
                                    role="img"
                                    aria-label="Grafik batang pendapatan servis dan barang selama 7 hari terakhir">
                                    Data grafik pendapatan 7 hari terakhir.
                                </canvas>
                            </div>
                        </div>

                        {{-- Donut status pekerjaan --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Status pekerjaan</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Bulan ini · {{ $ownerStats['total_transaksi_bulan'] }} total</p>
                            <div class="relative h-36 mb-3">
                                <canvas id="chartStatusPekerjaan"
                                    role="img"
                                    aria-label="Donut chart status pekerjaan bulan ini: selesai, proses, belum dikerjakan">
                                    Status pekerjaan: selesai {{ $ownerStats['status_selesai'] }}, proses {{ $ownerStats['status_proses'] }}, belum {{ $ownerStats['status_belum'] }}.
                                </canvas>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-green-600 inline-block"></span>Selesai</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $ownerStats['status_selesai'] }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>Sedang proses</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $ownerStats['status_proses'] }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-orange-400 inline-block"></span>Belum dikerjakan</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $ownerStats['status_belum'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Bottom Row: Top Jasa + Alerts ── --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                        {{-- Top jasa terlaris --}}
                        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Jasa & barang terlaris</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Berdasarkan frekuensi transaksi bulan ini</p>
                            <div class="space-y-3">
                                @forelse($ownerStats['top_jasa'] as $i => $item)
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-400 w-4 text-center">{{ $i + 1 }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 w-32 truncate">{{ $item['nama'] }}</span>
                                    <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-600 rounded-full"
                                            style="width: {{ $item['pct'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-900 dark:text-white w-16 text-right">{{ $item['count'] }}x</span>
                                    <span class="text-xs text-gray-400 w-24 text-right hidden sm:block">Rp{{ number_format($item['total'], 0, ',', '.') }}</span>
                                </div>
                                @empty
                                <p class="text-xs text-gray-400 text-center py-4">Belum ada data</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Alert panel --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Perhatian</h3>

                            {{-- Stok hampir habis --}}
                            @if($ownerStats['stok_menipis'] > 0)
                            <div class="flex gap-2.5 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg">
                                <svg class="w-4 h-4 text-amber-700 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-semibold text-amber-800 dark:text-amber-300">Stok hampir habis</p>
                                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">{{ $ownerStats['stok_menipis'] }} barang di bawah minimum. Perlu restock.</p>
                                    <a href="/barang/stok" class="text-xs text-amber-800 dark:text-amber-300 underline mt-1 inline-block">Lihat inventory →</a>
                                </div>
                            </div>
                            @endif

                            {{-- Piutang lebih dari 7 hari --}}
                            @if($ownerStats['piutang_lama'] > 0)
                            <div class="flex gap-2.5 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                                <svg class="w-4 h-4 text-red-700 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-semibold text-red-800 dark:text-red-300">Piutang &gt;7 hari</p>
                                    <p class="text-xs text-red-700 dark:text-red-400 mt-0.5">{{ $ownerStats['piutang_lama'] }} transaksi belum dibayar lebih dari 7 hari.</p>
                                    <a href="{{ route('riwayat.service') }}" class="text-xs text-red-800 dark:text-red-300 underline mt-1 inline-block">Lihat riwayat →</a>
                                </div>
                            </div>
                            @endif

                            {{-- Tren pendapatan --}}
                            <div class="flex gap-2.5 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                                <svg class="w-4 h-4 text-blue-700 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-semibold text-blue-800 dark:text-blue-300">Tren pendapatan</p>
                                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5">
                                        {{ $ownerStats['pct_pendapatan'] >= 0 ? 'Naik' : 'Turun' }} {{ abs($ownerStats['pct_pendapatan']) }}% dibanding bulan lalu.
                                    </p>
                                    <a href="{{ route('laporan.bulanan') }}" class="text-xs text-blue-800 dark:text-blue-300 underline mt-1 inline-block">Lihat laporan →</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endif

            {{-- ── Menu Cards (Kasir & Owner) ── --}}
            @if(in_array(Auth::user()->role, ['kasir', 'owner']))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Transaksi Baru</h3>
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Buat transaksi servis atau penjualan</p>
                    <button onclick="document.getElementById('transaksiModal').classList.remove('hidden')"
                        class="w-full text-xs px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Kelola Transaksi
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Daftar Transaksi</h3>
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Lihat dan kelola semua transaksi</p>
                    <button onclick="document.getElementById('RiwayatransaksiModal').classList.remove('hidden')"
                        class="w-full text-xs px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Transaksi
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Inventory</h3>
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Kelola stok suku cadang</p>
                    <button onclick="document.getElementById('inventoryModal').classList.remove('hidden')"
                        class="w-full text-xs px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        Kelola Inventory
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pengeluaran</h3>
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Laporan pengeluaran operasional</p>
                    <a href="{{ route('pengeluaran.operasional') }}"
                        class="block w-full text-center text-xs px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                        Kelola Operasional
                    </a>
                </div>
            </div>
            @endif

            {{-- ── Menu Cards (Keuangan & Owner) ── --}}
            @if(in_array(Auth::user()->role, ['keuangan', 'owner']))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Laporan Mingguan</h3>
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Ringkasan pendapatan & pengeluaran tiap minggu</p>
                    <a href="{{ route('laporan.mingguan') }}" class="block w-full text-center text-xs px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Lihat Laporan</a>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Laporan Bulanan</h3>
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Rekap bulanan untuk evaluasi keuangan bengkel</p>
                    <a href="{{ route('laporan.bulanan') }}" class="block w-full text-center text-xs px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Lihat Laporan</a>
                </div>
            </div>
            @endif

            {{-- ── Tabel Riwayat Transaksi Terbaru ── --}}
            @if(in_array(Auth::user()->role, ['kasir', 'owner']))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Transaksi Terbaru</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $limit }} transaksi terakhir</p>
                        </div>
                    </div>
                    <a href="{{ route('riwayat.service') }}"
                       class="text-xs px-3 py-1.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Invoice</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Pelanggan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide hidden md:table-cell">Kendaraan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Total</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide hidden sm:table-cell">Status</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide hidden sm:table-cell">Bayar</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide hidden lg:table-cell">Tanggal</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentTransactions as $transaction)
                                <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors">
                                    {{-- Invoice --}}
                                    <td class="px-5 py-3.5">
                                        <span class="font-semibold text-blue-600 dark:text-blue-400 text-xs">
                                            {{ $transaction->invoice }}
                                        </span>
                                    </td>

                                    {{-- Pelanggan --}}
                                    <td class="px-5 py-3.5">
                                        <div class="font-medium text-gray-900 dark:text-white text-xs">
                                            {{ $transaction->pelangganMobil->nama_pelanggan }}
                                        </div>
                                        @if($transaction->pelangganMobil->kontak)
                                            <div class="text-xs text-gray-400">{{ $transaction->pelangganMobil->kontak }}</div>
                                        @endif
                                    </td>

                                    {{-- Kendaraan --}}
                                    <td class="px-5 py-3.5 hidden md:table-cell">
                                        <div class="font-medium text-gray-900 dark:text-white text-xs">
                                            {{ $transaction->pelangganMobil->nopol }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $transaction->pelangganMobil->merk_mobil }} {{ $transaction->pelangganMobil->tipe_mobil }}
                                        </div>
                                    </td>

                                    {{-- Total --}}
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-green-600 dark:text-green-400 text-xs">
                                            Rp{{ number_format($transaction->total_keseluruhan, 0, ',', '.') }}
                                        </div>
                                        @if($transaction->sisa_pembayaran > 0)
                                            <div class="text-xs text-orange-500">
                                                Sisa: Rp{{ number_format($transaction->sisa_pembayaran, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Status Pekerjaan --}}
                                    <td class="px-5 py-3.5 text-center hidden sm:table-cell">
                                        @if($transaction->status_pekerjaan === 'belum_dikerjakan')
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300 rounded-full">
                                                Belum
                                            </span>
                                        @elseif($transaction->status_pekerjaan === 'sedang_dikerjakan')
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 rounded-full">
                                                Proses
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 rounded-full">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status Pembayaran --}}
                                    <td class="px-5 py-3.5 text-center hidden sm:table-cell">
                                        @php $sisa = $transaction->sisa_pembayaran ?? 0; @endphp
                                        @if($sisa == 0)
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 rounded-full">Lunas</span>
                                        @elseif($transaction->status_pembayaran === 'sebagian')
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300 rounded-full">Sebagian</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 rounded-full">Belum</span>
                                        @endif
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="px-5 py-3.5 hidden lg:table-cell">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    {{-- Aksi — memanggil method dari RiwayatService via @this --}}
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-center gap-1 flex-wrap">
                                            {{-- Detail --}}
                                            <button wire:click="showDetail({{ $transaction->id }})"
                                                    class="p-1.5 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-200 transition-colors"
                                                    title="Detail">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- Edit --}}
                                            <button wire:click="showEdit({{ $transaction->id }})"
                                                    class="p-1.5 bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-lg hover:bg-purple-200 transition-colors"
                                                    title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            {{-- Tambah Item --}}
                                            <button wire:click="showAddItem({{ $transaction->id }})"
                                                    class="p-1.5 bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-lg hover:bg-green-200 transition-colors"
                                                    title="Tambah Item">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </button>

                                            {{-- Bayar --}}
                                            <button wire:click="showPayment({{ $transaction->id }})"
                                                    class="p-1.5 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 rounded-lg hover:bg-yellow-200 transition-colors"
                                                    title="Bayar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </button>

                                            {{-- Cetak Invoice --}}
                                            <button wire:click="printInvoice({{ $transaction->id }})"
                                                    class="p-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition-colors"
                                                    title="Cetak Invoice">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </button>

                                            {{-- Hapus --}}
                                            <button wire:click="confirmDelete({{ $transaction->id }})"
                                                    class="p-1.5 bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 transition-colors"
                                                    title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-sm font-medium">Belum ada transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MODALS — semua dari partials yang sama dengan riwayat-service
         Tidak ada duplikasi; cukup @include sekali di sini.
    ══════════════════════════════════════════════════════════ --}}
    @include('livewire.partials.modal-detail')
    @include('livewire.partials.modal-discount')
    @include('livewire.partials.modal-edit')
    @include('livewire.partials.modal-add-item')
    @include('livewire.partials.modal-edit-item')
    @include('livewire.partials.modal-delete-item')
    @include('livewire.partials.modal-payment-detail')
    @include('livewire.partials.modal-payment')
    @include('livewire.partials.modal-delete-confirm')

    {{-- ── Static Navigation Modals ── --}}
    <div id="inventoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-xl shadow-xl p-8 relative">
            <button onclick="document.getElementById('inventoryModal').classList.add('hidden')"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Pilih Aksi Inventory</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="/barang" class="flex items-center gap-4 bg-orange-50 border border-orange-200 p-4 rounded-xl hover:bg-orange-100 transition">
                    <div class="p-3 bg-orange-500 text-white rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                    <div><p class="font-semibold text-sm text-gray-800">Tambah Barang</p><p class="text-xs text-gray-500">Masukkan data barang baru</p></div>
                </a>
                <a href="/barang/stok" class="flex items-center gap-4 bg-orange-50 border border-orange-200 p-4 rounded-xl hover:bg-orange-100 transition">
                    <div class="p-3 bg-orange-500 text-white rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"/></svg></div>
                    <div><p class="font-semibold text-sm text-gray-800">Tambah Stok</p><p class="text-xs text-gray-500">Tambah stok barang yang ada</p></div>
                </a>
            </div>
        </div>
    </div>

    <div id="transaksiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-xl shadow-xl p-8 relative">
            <button onclick="document.getElementById('transaksiModal').classList.add('hidden')"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Pilih Aksi Transaksi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('transaksi.services') }}" class="flex items-center gap-4 bg-green-50 border border-green-200 p-4 rounded-xl hover:bg-green-100 transition">
                    <div class="p-3 bg-green-500 text-white rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                    <div><p class="font-semibold text-sm text-gray-800">Transaksi Servis</p><p class="text-xs text-gray-500">Servis mobil pelanggan</p></div>
                </a>
                <a href="{{ route('transaksi.barang') }}" class="flex items-center gap-4 bg-green-50 border border-green-200 p-4 rounded-xl hover:bg-green-100 transition">
                    <div class="p-3 bg-green-500 text-white rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"/></svg></div>
                    <div><p class="font-semibold text-sm text-gray-800">Penjualan Barang</p><p class="text-xs text-gray-500">Penjualan suku cadang</p></div>
                </a>
            </div>
        </div>
    </div>

    <div id="RiwayatransaksiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-xl shadow-xl p-8 relative">
            <button onclick="document.getElementById('RiwayatransaksiModal').classList.add('hidden')"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Pilih Riwayat Transaksi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('riwayat.service') }}" class="flex items-center gap-4 bg-blue-50 border border-blue-200 p-4 rounded-xl hover:bg-blue-100 transition">
                    <div class="p-3 bg-blue-500 text-white rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                    <div><p class="font-semibold text-sm text-gray-800">Riwayat Servis</p><p class="text-xs text-gray-500">Riwayat transaksi servis mobil</p></div>
                </a>
                <a href="{{ route('riwayat.transaksi.barang') }}" class="flex items-center gap-4 bg-blue-50 border border-blue-200 p-4 rounded-xl hover:bg-blue-100 transition">
                    <div class="p-3 bg-blue-500 text-white rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"/></svg></div>
                    <div><p class="font-semibold text-sm text-gray-800">Riwayat Barang</p><p class="text-xs text-gray-500">Riwayat penjualan barang</p></div>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Toast & Keyboard Shortcut Script ── --}}
    <script>
    document.addEventListener('livewire:init', () => {
        const toastMap = {
            'payment-added'       : 'Pembayaran berhasil ditambahkan!',
            'payment-updated'     : 'Pembayaran berhasil diupdate!',
            'transaction-updated' : 'Transaksi berhasil diupdate!',
            'item-added'          : 'Item berhasil ditambahkan!',
            'items-added'         : 'Item berhasil disimpan!',
            'item-updated'        : 'Item berhasil diupdate!',
            'item-deleted'        : 'Item berhasil dihapus!',
            'transaction-deleted' : 'Transaksi berhasil dihapus!',
            'discount-updated'    : 'Diskon berhasil diperbarui!',
        };

        Object.entries(toastMap).forEach(([event, msg]) => {
            Livewire.on(event, () => showToast(msg, 'success'));
        });

        Livewire.on('open-payment-proof', (data) => {
            window.open(data[0].url, '_blank');
        });
    });

    function showToast(message, type = 'success') {
        document.querySelectorAll('.dash-toast').forEach(t => t.remove());
        const colors = { success: 'bg-green-600', error: 'bg-red-600' };
        const el = document.createElement('div');
        el.className = `dash-toast fixed top-5 right-5 z-[9999] ${colors[type] ?? 'bg-gray-700'} text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg flex items-center gap-2`;
        el.innerHTML = `
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
            ${message}`;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3500);
    }

    // Escape: tutup Livewire modal + static modal
    document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    
    if (typeof Livewire !== 'undefined') {
        // Di Livewire v3, dispatch event ke semua komponen
        Livewire.dispatch('close-modal');
    }

    ['inventoryModal','transaksiModal','RiwayatransaksiModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el && !el.classList.contains('hidden')) el.classList.add('hidden');
    });
});
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const tickColor = isDark ? '#9ca3af' : '#6b7280';

    const labels7 = @json($ownerStats['labels_7_hari']);
    const dataServis = @json($ownerStats['data_servis_7_hari']);
    const dataBarang = @json($ownerStats['data_barang_7_hari']);

    new Chart(document.getElementById('chartPendapatan7Hari'), {
        type: 'bar',
        data: {
            labels: labels7,
            datasets: [
                {
                    label: 'Servis',
                    data: dataServis,
                    backgroundColor: '#2563eb',
                    borderRadius: 4,
                    stack: 'stack'
                },
                {
                    label: 'Barang',
                    data: dataBarang,
                    backgroundColor: '#16a34a',
                    borderRadius: 4,
                    stack: 'stack'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: tickColor, font: { size: 11 } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        color: tickColor,
                        font: { size: 11 },
                        callback: v => v >= 1000000 ? 'Rp' + (v/1000000).toFixed(1) + 'jt' : 'Rp' + (v/1000) + 'rb'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('chartStatusPekerjaan'), {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Proses', 'Belum'],
            datasets: [{
                data: [
                    {{ $ownerStats['status_selesai'] }},
                    {{ $ownerStats['status_proses'] }},
                    {{ $ownerStats['status_belum'] }}
                ],
                backgroundColor: ['#16a34a', '#3b82f6', '#f97316'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>

</div>