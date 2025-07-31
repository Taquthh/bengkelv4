<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">
                                Selamat Datang, {{ Auth::user()->name }}!
                            </h1>
                            <p class="text-blue-100 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Role: {{ ucfirst(Auth::user()->role) }} - FJS Auto Service
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @if(Auth::user()->role === 'owner')
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pendapatan Hari Ini</h3>
                                <p class="text-2xl font-bold text-green-600">Rp 2.450.000</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(in_array(Auth::user()->role, ['kasir', 'owner']))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Hari Ini</h3>
                                <p class="text-2xl font-bold text-blue-600">12</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(in_array(Auth::user()->role, ['kasir', 'owner']))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sedang Dikerjakan</h3>
                                <p class="text-2xl font-bold text-yellow-600">5</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->role === 'owner')
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Staff</h3>
                                <p class="text-2xl font-bold text-purple-600">8</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu Berdasarkan Role -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @if(Auth::user()->role === 'kasir')
                    <!-- Menu untuk Kasir -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaksi Baru</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Buat transaksi servis atau penjualan suku cadang
                        </p>
                        <button onclick="document.getElementById('transaksiModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Kelola Transaksi
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Transaksi</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-full">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Lihat dan kelola semua transaksi</p>
                        <button onclick="document.getElementById('RiwayatransaksiModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Lihat Transaksi
                        </button>
                    </div>

                     <!-- Tombol Trigger -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory</h3>
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Kelola stok suku cadang dan perlengkapan</p>
                    <button onclick="document.getElementById('inventoryModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        Kelola Inventory
                    </button>
                </div>

                <!-- Modal Inventory -->
                <div id="inventoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-lg shadow-xl p-8 relative">
                        <!-- Tombol Close -->
                        <button onclick="document.getElementById('inventoryModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <!-- Isi Modal -->
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h18v4H3V3zm0 6h18v12H3V9z"/>
                            </svg>
                            Pilih Aksi Inventory
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Tambah Barang -->
                            <a href="/barang" class="flex items-center gap-4 bg-orange-100 dark:bg-orange-950 border border-orange-300 dark:border-orange-700 p-4 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800 transition">
                                <div class="p-3 bg-orange-500 text-white rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Barang</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan data barang baru</p>
                                </div>
                            </a>

                            <!-- Tambah Stok -->
                            <a href="/barang/stok" class="flex items-center gap-4 bg-orange-100 dark:bg-orange-950 border border-orange-300 dark:border-orange-700 p-4 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800 transition">
                                <div class="p-3 bg-orange-500 text-white rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v8m4-4H8"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Stok</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Menambah stok barang yang sudah ada</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal Transaksi -->
                <div id="transaksiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-lg shadow-xl p-8 relative">
                        <!-- Tombol Close -->
                        <button onclick="document.getElementById('transaksiModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <!-- Isi Modal -->
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h18v4H3V3zm0 6h18v12H3V9z"/>
                            </svg>
                            Pilih Aksi Transaksi
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Transaksi Servis Mobil -->
                            <a href="{{ route('transaksi.services') }}" class="flex items-center gap-4 bg-green-100 dark:bg-green-950 border border-green-300 dark:border-green-700 p-4 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition">
                                <div class="p-3 bg-green-500 text-white rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Transaksi Servis Mobil</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan detail transaksi servis mobil pelanggan</p>
                                </div>
                            </a>

                            <!-- Transaksi Penjualan Barang -->
                            <a href="{{ route('transaksi.barang') }}" class="flex items-center gap-4 bg-green-100 dark:bg-green-950 border border-green-300 dark:border-green-700 p-4 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition">
                                <div class="p-3 bg-green-500 text-white rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v8m4-4H8"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Transaksi Penjualan Barang</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan detail transaksi penjualan barang ke pelanggan</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>



                <!-- Modal Riwayat Transaksi -->
                <div id="RiwayatransaksiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-lg shadow-xl p-8 relative">
                        <!-- Tombol Close -->
                        <button onclick="document.getElementById('RiwayatransaksiModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <!-- Isi Modal -->
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h18v4H3V3zm0 6h18v12H3V9z"/>
                            </svg>
                            Pilih Aksi Transaksi
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Tombol untuk melihat riwayat servis mobil -->
                            <a href="{{ route('riwayat.service') }}" class="flex items-center gap-4 bg-blue-100 dark:bg-blue-950 border border-blue-300 dark:border-blue-700 p-4 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                <div class="p-3 bg-blue-500 text-white rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Servis Mobil</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lihat daftar riwayat transaksi servis mobil pelanggan</p>
                                </div>
                            </a>

                            <!-- Tombol untuk melihat riwayat penjualan barang -->
                            <a href="{{ route('riwayat.transaksi.barang') }}" class="flex items-center gap-4 bg-blue-100 dark:bg-blue-950 border border-blue-300 dark:border-blue-700 p-4 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                <div class="p-3 bg-blue-500 text-white rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v8m4-4H8"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Transaksi Barang</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lihat daftar riwayat transaksi penjualan barang</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>


                @endif

                @if(Auth::user()->role === 'keuangan')
                    <!-- Menu untuk Keuangan -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Keuangan</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Buat laporan pendapatan dan pengeluaran</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Lihat Laporan
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kelola Pengeluaran</h3>
                            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Catat dan kelola pengeluaran operasional</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Kelola Pengeluaran
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Piutang Customer</h3>
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Kelola piutang dan pembayaran customer</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            Kelola Piutang
                        </a>
                    </div>
                @endif

                @if(Auth::user()->role === 'owner')
                    <!-- Menu untuk Owner -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dashboard Analytics</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-full">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Lihat analisa bisnis dan performance</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Lihat Analytics
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kelola Staff</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-full">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Kelola user dan hak akses sistem</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Kelola Staff
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory</h3>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-full">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Kelola stok suku cadang dan perlengkapan</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            Kelola Inventory
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Komprehensif</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Laporan menyeluruh untuk analisa bisnis</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Lihat Laporan
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Sistem</h3>
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-full">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Konfigurasi sistem dan pengaturan umum</p>
                        <a href="" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Pengaturan
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>