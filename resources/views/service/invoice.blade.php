<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 mt-16">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header with Actions -->
            <div class="bg-white bg-opacity-90 backdrop-blur-xl rounded-3xl shadow-xl border border-white border-opacity-30 p-6 mb-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-gray-900 via-green-800 to-emerald-800 bg-clip-text text-transparent">
                                Invoice Service
                            </h1>
                            <p class="text-gray-500 font-medium text-base sm:text-lg">{{ $transaksi->invoice }}</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3 flex-wrap">
                        <a href="{{ route('transaksi.services') }}" 
                        class="px-4 sm:px-6 py-2 sm:py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all duration-300 flex items-center space-x-2 no-print">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Kembali</span>
                        </a>
                        <a href="{{ route('service.invoice.print', $transaksi->id) }}" target="_blank"
                           class="px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 font-semibold transition-all duration-300 flex items-center space-x-2 no-print">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Cetak</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Invoice Content -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden" id="invoice-content">
                <!-- Invoice Header -->
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl sm:text-3xl font-bold mb-2">FJS Auto Service</h2>
                            <p class="text-blue-100 text-sm sm:text-lg">Jl. Veteran No.123, Banjarmasin</p>
                            <p class="text-blue-100 text-sm">📞 0813 4841 0569 (Pa Taufik)</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <div class="text-2xl sm:text-4xl font-bold">INVOICE</div>
                            <div class="text-lg sm:text-xl mt-2">{{ $transaksi->invoice }}</div>
                            <div class="text-blue-100 mt-1 text-sm sm:text-base">{{ \Carbon\Carbon::parse($transaksi->tanggal_service)->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Customer & Vehicle Info -->
                <div class="p-6 sm:p-8 border-b border-gray-200">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Pelanggan
                            </h3>
                            <div class="space-y-2 text-gray-700 text-sm sm:text-base">
                                <div><strong>{{ $transaksi->pelangganMobil->nama_pelanggan }}</strong></div>
                                @if($transaksi->pelangganMobil->kontak)
                                    <div>📞 {{ $transaksi->pelangganMobil->kontak }}</div>
                                @endif
                                <div class="capitalize">{{ $transaksi->pelangganMobil->jenis_pelanggan }}</div>
                                @if($transaksi->pelangganMobil->nama_perusahaan)
                                    <div>{{ $transaksi->pelangganMobil->nama_perusahaan }}</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Informasi Kendaraan
                            </h3>
                            <div class="space-y-2 text-gray-700 text-sm sm:text-base">
                                <div><strong>{{ $transaksi->pelangganMobil->nopol }}</strong></div>
                                <div>{{ $transaksi->pelangganMobil->merk_mobil }} {{ $transaksi->pelangganMobil->tipe_mobil }}</div>
                                @if($transaksi->pelangganMobil->tahun)
                                    <div>Tahun: {{ $transaksi->pelangganMobil->tahun }}</div>
                                @endif
                                @if($transaksi->pelangganMobil->warna)
                                    <div>Warna: {{ $transaksi->pelangganMobil->warna }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="p-6 sm:p-8 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Detail Service
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-gray-600 uppercase tracking-wide">Keluhan</label>
                            <p class="text-gray-800 mt-1 text-sm sm:text-base">{{ $transaksi->keluhan }}</p>
                        </div>
                        @if($transaksi->diagnosa)
                            <div>
                                <label class="text-xs sm:text-sm font-semibold text-gray-600 uppercase tracking-wide">Diagnosa</label>
                                <p class="text-gray-800 mt-1 text-sm sm:text-base">{{ $transaksi->diagnosa }}</p>
                            </div>
                        @endif
                        @if($transaksi->pekerjaan_dilakukan)
                            <div>
                                <label class="text-xs sm:text-sm font-semibold text-gray-600 uppercase tracking-wide">Pekerjaan yang Dilakukan</label>
                                <p class="text-gray-800 mt-1 text-sm sm:text-base">{{ $transaksi->pekerjaan_dilakukan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Information - New Section -->
                <div class="p-6 sm:p-8 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status Service & Pembayaran
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Work Status -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide block mb-2">Status Pekerjaan</label>
                            <div class="flex items-center space-x-2">
                                @php
                                    $workStatusClass = match($transaksi->status_pekerjaan) {
                                        'belum_dikerjakan' => 'bg-red-100 text-red-800',
                                        'sedang_dikerjakan' => 'bg-yellow-100 text-yellow-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $workStatusText = match($transaksi->status_pekerjaan) {
                                        'belum_dikerjakan' => 'BELUM DIKERJAKAN',
                                        'sedang_dikerjakan' => 'SEDANG DIKERJAKAN',
                                        'selesai' => 'SELESAI',
                                        default => strtoupper($transaksi->status_pekerjaan)
                                    };
                                @endphp
                                <span class="px-3 py-1 {{ $workStatusClass }} rounded-full text-xs font-bold">
                                    {{ $workStatusText }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Strategy -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide block mb-2">Strategi Pembayaran</label>
                            <div class="flex items-center space-x-2">
                                @php
                                    $strategyText = match($transaksi->strategi_pembayaran) {
                                        'bayar_akhir' => 'BAYAR AKHIR',
                                        'bayar_dimuka' => 'BAYAR DIMUKA',
                                        'cicilan' => 'CICILAN',
                                        default => strtoupper($transaksi->strategi_pembayaran)
                                    };
                                @endphp
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                                    {{ $strategyText }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide block mb-2">Status Pembayaran</label>
                            <div class="flex items-center space-x-2">
                                @php
                                    $paymentStatusClass = match($transaksi->status_pembayaran) {
                                        'lunas' => 'bg-green-100 text-green-800',
                                        'sebagian' => 'bg-orange-100 text-orange-800',
                                        'belum_bayar' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $paymentStatusText = match($transaksi->status_pembayaran) {
                                        'lunas' => 'LUNAS',
                                        'sebagian' => 'SEBAGIAN',
                                        'belum_bayar' => 'BELUM BAYAR',
                                        default => strtoupper($transaksi->status_pembayaran)
                                    };
                                @endphp
                                <span class="px-3 py-1 {{ $paymentStatusClass }} rounded-full text-xs font-bold">
                                    {{ $paymentStatusText }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if($transaksi->no_surat_pesanan)
                        <div class="mt-4">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">No. Surat Pesanan</label>
                            <p class="text-gray-800 mt-1">{{ $transaksi->no_surat_pesanan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Items Table -->
                <div class="p-6 sm:p-8">
                    <!-- Barang Items -->
                    @if($transaksi->serviceBarangItems->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Spare Parts & Barang
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 border-b-2 border-gray-200">
                                            <th class="text-left p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">No</th>
                                            <th class="text-left p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Nama Barang</th>
                                            <th class="text-center p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Qty</th>
                                            <th class="text-right p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Harga Satuan</th>
                                            <th class="text-right p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi->serviceBarangItems as $index => $item)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="p-3 sm:p-4 text-gray-600 text-xs sm:text-sm">{{ $index + 1 }}</td>
                                                <td class="p-3 sm:p-4">
                                                    <div class="font-medium text-gray-900 text-xs sm:text-sm">{{ $item->barang->nama }}</div>
                                                    @if($item->barang->merk || $item->barang->tipe)
                                                        <div class="text-xs text-gray-500">
                                                            {{ $item->barang->merk }} {{ $item->barang->tipe }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="p-3 sm:p-4 text-center font-medium text-xs sm:text-sm">{{ $item->jumlah }}</td>
                                                <td class="p-3 sm:p-4 text-right font-medium text-xs sm:text-sm">Rp{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                                <td class="p-3 sm:p-4 text-right font-bold text-green-600 text-xs sm:text-sm">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Jasa Items -->
                    @if($transaksi->serviceJasaItems->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Jasa Service
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 border-b-2 border-gray-200">
                                            <th class="text-left p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">No</th>
                                            <th class="text-left p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Nama Jasa</th>
                                            <th class="text-left p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Keterangan</th>
                                            <th class="text-right p-3 sm:p-4 font-semibold text-gray-700 text-xs sm:text-sm">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi->serviceJasaItems as $index => $jasa)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="p-3 sm:p-4 text-gray-600 text-xs sm:text-sm">{{ $index + 1 }}</td>
                                                <td class="p-3 sm:p-4 font-medium text-gray-900 text-xs sm:text-sm">{{ $jasa->nama_jasa }}</td>
                                                <td class="p-3 sm:p-4 text-gray-600 text-xs sm:text-sm">{{ $jasa->keterangan ?: '-' }}</td>
                                                <td class="p-3 sm:p-4 text-right font-bold text-purple-600 text-xs sm:text-sm">Rp{{ number_format($jasa->harga_jasa, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Summary -->
                    <div class="border-t-2 border-gray-200 pt-6">
                        <div class="flex justify-end">
                            <div class="w-full sm:w-80">
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm sm:text-lg">
                                        <span class="text-gray-600">Total Barang:</span>
                                        <span class="font-semibold">Rp{{ number_format($transaksi->total_barang, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm sm:text-lg">
                                        <span class="text-gray-600">Total Jasa:</span>
                                        <span class="font-semibold">Rp{{ number_format($transaksi->total_jasa, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg sm:text-2xl font-bold border-t-2 border-gray-200 pt-3">
                                        <span class="text-gray-900">Total Keseluruhan:</span>
                                        <span class="text-green-600">Rp{{ number_format($transaksi->total_keseluruhan, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <!-- Updated Payment Info Section -->
                                    <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm sm:text-base">
                                                <span class="text-gray-600">Metode Pembayaran:</span>
                                                <span class="font-semibold capitalize">{{ $transaksi->metode_pembayaran }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm sm:text-base">
                                                <span class="text-gray-600">Strategi Pembayaran:</span>
                                                <span class="font-semibold capitalize">
                                                    @php
                                                        echo match($transaksi->strategi_pembayaran) {
                                                            'bayar_akhir' => 'Bayar Akhir',
                                                            'bayar_dimuka' => 'Bayar Dimuka',
                                                            'cicilan' => 'Cicilan',
                                                            default => $transaksi->strategi_pembayaran
                                                        };
                                                    @endphp
                                                </span>
                                            </div>
                                            <div class="flex justify-between text-sm sm:text-base">
                                                <span class="text-gray-600">Status Pembayaran:</span>
                                                <span class="font-semibold uppercase
                                                    @if($transaksi->status_pembayaran == 'lunas') text-green-600
                                                    @elseif($transaksi->status_pembayaran == 'sebagian') text-orange-600
                                                    @elseif($transaksi->status_pembayaran == 'belum_bayar') text-red-600
                                                    @else text-gray-600 @endif">
                                                    {{ $transaksi->status_pembayaran }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between text-sm sm:text-base">
                                                <span class="text-gray-600">Sudah Dibayar:</span>
                                                <span class="font-semibold">Rp{{ number_format($transaksi->total_sudah_dibayar, 0, ',', '.') }}</span>
                                            </div>
                                            @if($transaksi->sisa_pembayaran > 0)
                                                <div class="flex justify-between text-sm sm:text-base">
                                                    <span class="text-gray-600">Sisa Pembayaran:</span>
                                                    <span class="font-bold text-red-600">Rp{{ number_format($transaksi->sisa_pembayaran, 0, ',', '.') }}</span>
                                                </div>
                                                @if($transaksi->jatuh_tempo)
                                                    <div class="flex justify-between text-sm sm:text-base">
                                                        <span class="text-gray-600">Jatuh Tempo:</span>
                                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($transaksi->jatuh_tempo)->format('d F Y') }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                            
                                            @if($transaksi->keterangan_pembayaran)
                                                <div class="pt-2 border-t border-gray-200">
                                                    <span class="text-gray-600 text-xs font-semibold uppercase tracking-wide">Keterangan:</span>
                                                    <p class="text-gray-800 text-sm mt-1">{{ $transaksi->keterangan_pembayaran }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History Section - New -->
                    @if($transaksi->servicePayments && $transaksi->servicePayments->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Riwayat Pembayaran
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200">
                                            <th class="text-left p-3 font-semibold text-gray-700 text-sm">No</th>
                                            <th class="text-left p-3 font-semibold text-gray-700 text-sm">Tanggal</th>
                                            <th class="text-left p-3 font-semibold text-gray-700 text-sm">Metode</th>
                                            <th class="text-right p-3 font-semibold text-gray-700 text-sm">Jumlah</th>
                                            <th class="text-left p-3 font-semibold text-gray-700 text-sm">Kasir</th>
                                            <th class="text-left p-3 font-semibold text-gray-700 text-sm">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi->servicePayments as $index => $payment)
                                            <tr class="border-b border-gray-100">
                                                <td class="p-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                                                <td class="p-3 text-sm">{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d/m/Y') }}</td>
                                                <td class="p-3 text-sm capitalize">
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                                        {{ $payment->metode_pembayaran }}
                                                    </span>
                                                </td>
                                                <td class="p-3 text-sm font-bold text-green-600 text-right">
                                                    Rp{{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                                                </td>
                                                <td class="p-3 text-sm">{{ $payment->kasir }}</td>
                                                <td class="p-3 text-sm text-gray-600">{{ $payment->keterangan ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-gray-50 font-bold">
                                            <td colspan="3" class="p-3 text-sm text-right">Total Dibayar:</td>
                                            <td class="p-3 text-sm text-green-600 text-right">
                                                Rp{{ number_format($transaksi->servicePayments->sum('jumlah_bayar'), 0, ',', '.') }}
                                            </td>
                                            <td colspan="2" class="p-3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm sm:text-base">
                        <div class="mb-2">
                            <strong>Kasir:</strong> {{ $transaksi->kasir }}
                        </div>
                        <div class="text-xs sm:text-sm">
                            Terima kasih atas kepercayaan Anda menggunakan jasa kami.
                        </div>
                        <div class="text-xs sm:text-sm mt-1">
                            Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles for better compatibility */
        .bg-opacity-90 {
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .border-opacity-30 {
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        /* Print styles */
        @media print {
            body { 
                background: white !important; 
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .min-h-screen {
                min-height: auto !important;
            }
            
            .mt-16 {
                margin-top: 0 !important;
            }
            
            .bg-gradient-to-br,
            .bg-opacity-90,
            .backdrop-blur-xl { 
                background: white !important; 
            }
            
            #invoice-content {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            
            .no-print,
            .print\\:hidden { 
                display: none !important; 
            }
            
            .shadow-xl,
            .shadow-lg {
                box-shadow: none !important;
            }
            
            .rounded-3xl,
            .rounded-2xl,
            .rounded-xl {
                border-radius: 8px !important;
            }
            
            /* Ensure colors print correctly */
            .bg-gradient-to-r {
                background: #4F46E5 !important;
                color: white !important;
            }
            
            .text-green-600 {
                color: #059669 !important;
            }
            
            .text-purple-600 {
                color: #7C3AED !important;
            }
            
            .text-red-600 {
                color: #DC2626 !important;
            }
            
            .text-blue-600 {
                color: #2563EB !important;
            }
            
            .text-orange-600 {
                color: #EA580C !important;
            }
            
            .text-indigo-600 {
                color: #4F46E5 !important;
            }
            
            /* Status badges print styles */
            .bg-green-100 { background-color: #dcfce7 !important; }
            .bg-yellow-100 { background-color: #fef3c7 !important; }
            .bg-red-100 { background-color: #fee2e2 !important; }
            .bg-blue-100 { background-color: #dbeafe !important; }
            .bg-orange-100 { background-color: #fed7aa !important; }
            
            .text-green-800 { color: #166534 !important; }
            .text-yellow-800 { color: #92400e !important; }
            .text-red-800 { color: #991b1b !important; }
            .text-blue-800 { color: #1e40af !important; }
            .text-orange-800 { color: #9a3412 !important; }
        }
        
        /* Responsive table improvements */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                font-size: 12px;
            }
            
            .min-w-full {
                min-width: 600px;
            }
        }
        
        /* Enhanced status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Animation for status changes */
        .status-transition {
            transition: all 0.3s ease-in-out;
        }
        
        /* Enhanced print readability */
        @media print {
            .text-xs { font-size: 10px !important; }
            .text-sm { font-size: 12px !important; }
            .text-base { font-size: 14px !important; }
            .text-lg { font-size: 16px !important; }
            .text-xl { font-size: 18px !important; }
            .text-2xl { font-size: 20px !important; }
            
            /* Ensure tables don't break across pages */
            table, .table-container {
                page-break-inside: avoid;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            /* Ensure headers stick with content */
            h1, h2, h3, h4, h5, h6 {
                page-break-after: avoid;
            }
        }
    </style>

    <script>
        // Add any JavaScript for Livewire compatibility
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any interactive elements
            console.log('Updated invoice template loaded');
            
            // Add smooth transitions for status changes
            const statusElements = document.querySelectorAll('.status-badge');
            statusElements.forEach(element => {
                element.classList.add('status-transition');
            });
        });
        
        // Enhanced print function
        function printInvoice() {
            // Hide non-printable elements
            const elements = document.querySelectorAll('.no-print');
            elements.forEach(el => el.style.display = 'none');
            
            // Add print-specific classes
            document.body.classList.add('printing');
            
            window.print();
            
            // Restore elements after printing
            setTimeout(() => {
                elements.forEach(el => el.style.display = '');
                document.body.classList.remove('printing');
            }, 1000);
        }
        
        // Auto-refresh payment status if needed (for Livewire integration)
        if (window.Livewire) {
            window.Livewire.on('payment-updated', () => {
                console.log('Payment status updated');
                // You can add custom handling here
            });
            
            window.Livewire.on('transaction-updated', () => {
                console.log('Transaction updated');
                // You can add custom handling here
            });
        }
    </script>
</x-app-layout>