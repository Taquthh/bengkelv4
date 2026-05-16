<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaksi->invoice }}</title>
    <style>
        /* RESET & DASAR */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 8mm; /* Perkecil margin kertas agar konten lebih lega */
            size: A4;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.2; /* Dipersempit sedikit dari 1.3 */
            color: #080808;
            background: white;
            -webkit-print-color-adjust: exact; /* Pastikan warna tercetak */
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 5px;
            background: white;
            position: relative;
        }

        /* PERBAIKAN PRINT UNTUK MENCEGAH HALAMAN 2 */
        @media print {
            html, body {
                height: auto !important;
                overflow: visible !important;
            }
            .container {
                height: auto !important; /* Jangan dipaksa 297mm */
                overflow: visible !important;
                padding: 0;
                margin: 0;
            }
            /* Hilangkan elemen kosong atau jarak berlebih di akhir */
            .footer {
                page-break-after: avoid;
                margin-top: 10px; /* Kurangi margin top footer */
            }
            /* Pastikan tidak ada pemutusan halaman di tengah komponen penting */
            .summary-section, .signature-section, .info-section {
                page-break-inside: avoid;
            }
        }
        
        /* HEADER */
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .company-info h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }
        
        .company-info p {
            color: #0a0a0a;
            margin-bottom: 1px;
            font-size: 10px;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }
        
        .invoice-info .invoice-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .invoice-info .invoice-date {
            color: #070707;
            font-size: 10px;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .info-box {
            width: 48%;
        }
        
        .info-box h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid #000000;
        }
        
        .info-box p {
            margin-bottom: 2px;
            font-size: 10px;
        }
        
        .service-details {
            margin-bottom: 15px;
        }
        
        .service-details h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid #000000;
        }
        
        .service-item {
            margin-bottom: 8px;
        }
        
        .service-item label {
            font-weight: bold;
            color: #030507;
            display: block;
            margin-bottom: 2px;
            font-size: 10px;
        }
        
        .service-item p {
            color: #0e0e0f;
            font-size: 10px;
        }
        
        .items-section {
            margin-bottom: 15px;
        }
        
        .items-section h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid #07080a;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        th, td {
            padding: 4px;
            text-align: left;
            border-bottom: 1px solid #000000;
            font-size: 10px;
        }
        
        th {
            background-color: #ffffff;
            font-weight: bold;
            color: #000000;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .item-name {
            font-weight: bold;
            color: #111827;
        }
        
        .item-detail {
            font-size: 9px;
            color: #060607;
        }
        
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            gap: 15px;
        }
        
        .bank-contact-info {
            flex: 1;
        }
        
        .summary {
            width: 280px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 10px;
        }
        
        .summary-total {
            border-top: 2px solid #040405;
            font-weight: bold;
            font-size: 12px;
            padding-top: 6px;
            margin-top: 6px;
        }
        
        .payment-info {
            background-color: #f9fafb;
            padding: 8px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #909aad;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .status-piutang { color: #dc2626; }
        
        .bank-info h3, .contact-info h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid #909aad;
        }
        
        .bank-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9fafb;
            border-radius: 3px;
            border: 1px solid #909aad;
        }
        
        .bank-name {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 2px;
            font-size: 10px;
        }
        
        .account-info {
            font-size: 9px;
            color: #0b0c0e;
        }
        
        .account-number {
            font-weight: bold;
            color: #111827;
            font-size: 10px;
        }
        
        .contact-person {
            font-size: 11px;
            font-weight: bold;
            color: #dc2626;
            text-align: center;
            padding: 6px;
            background-color: #fef2f2;
            border: 1px solid #909aad;
            border-radius: 3px;
            margin-bottom: 4px;
        }
        
        .contact-number {
            font-size: 10px;
            font-weight: bold;
            color: #1e40af;
            text-align: center;
            padding: 4px;
            background-color: #eff6ff;
            border: 1px solid #909aad;
            border-radius: 3px;
        }
        
        .notes-section {
            margin-top: 8px;
            padding: 6px;
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 3px;
        }
        
        .notes-section div {
            font-size: 9px;
            color: #92400e;
        }
        
        .signature-section {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }
        
        .signature-box {
            text-align: center;
            width: 150px;
        }
        
        .signature-title {
            font-weight: bold;
            color: #010202;
            margin-bottom: 25px;
            padding-bottom: 2px;
            border-bottom: 1px solid #909aad;
            font-size: 11px;
        }
        
        .signature-line {
            border-bottom: 1px solid #050607;
            height: 30px;
            margin-bottom: 6px;
        }
        
        .signature-name {
            font-weight: bold;
            color: #111827;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        .footer p {
            margin-bottom: 3px;
            font-size: 9px;
        }
        
        @media print {
            body { 
                margin: 0;
                padding: 0;
                font-size: 10px;
            }
            .container {
                padding: 5px;
                box-shadow: none;
                max-width: none;
            }
            .no-print {
                display: none !important;
            }
            .summary-section,
            .signature-section {
                page-break-inside: avoid;
            }
            
            /* Ensure single page */
            .container {
                height: 297mm;
                overflow: hidden;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <h1>FJS Auto Service</h1>
                    <p>JL. PEKAPURAN RAYA SAMPING GG MERPATI IV RT 31 NO.34 LINGKAR DALAM SELATAN</p>
                    <p>BANJARMASIN</p>
                    <p>📞 0813 4841 0569 (Pa Taufik)</p>
                </div>
                <div class="invoice-info">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">{{ $transaksi->invoice }}</div>
                    <div class="invoice-date">{{ \Carbon\Carbon::parse($transaksi->tanggal_service)->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        </div>


        <!-- Customer & Vehicle Info -->
        <div class="info-section">
            <div class="info-box">
                <h3>📋 Informasi Pelanggan</h3>
                <p><strong>{{ $transaksi->pelangganMobil->nama_pelanggan }}</strong></p>
                @if($transaksi->pelangganMobil->kontak)
                    <p>📞 {{ $transaksi->pelangganMobil->kontak }}</p>
                @endif
                <p style="text-transform: capitalize;">{{ $transaksi->pelangganMobil->jenis_pelanggan }}</p>
                @if($transaksi->pelangganMobil->nama_perusahaan)
                    <p>{{ $transaksi->pelangganMobil->nama_perusahaan }}</p>
                @endif
            </div>
            <div class="info-box">
                <h3>🚗 Informasi Kendaraan</h3>
                <p><strong>{{ $transaksi->pelangganMobil->nopol }}</strong></p>
                <p>{{ $transaksi->pelangganMobil->merk_mobil }} {{ $transaksi->pelangganMobil->tipe_mobil }}</p>
                @if($transaksi->pelangganMobil->tahun)
                    <p>Tahun: {{ $transaksi->pelangganMobil->tahun }}</p>
                @endif
                @if($transaksi->pelangganMobil->warna)
                    <p>Warna: {{ $transaksi->pelangganMobil->warna }}</p>
                @endif
            </div>
        </div>

        <!-- Service Details -->
        <div class="service-details">
            <h3>🔧 Detail Service</h3>
            <div class="service-item">
                <label>Keluhan:</label>
                <p>{{ $transaksi->keluhan }}</p>
            </div>
            @if($transaksi->diagnosa)
                <div class="service-item">
                    <label>Diagnosa:</label>
                    <p>{{ $transaksi->diagnosa }}</p>
                </div>
            @endif
            @if($transaksi->pekerjaan_dilakukan)
                <div class="service-item">
                    <label>Pekerjaan yang Dilakukan:</label>
                    <p>{{ $transaksi->pekerjaan_dilakukan }}</p>
                </div>
            @endif
        </div>

        <!-- Barang Items -->
        @if($transaksi->serviceBarangItems->count() > 0)
            <div class="items-section">
                <h3>📦 Spare Parts & Barang</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="50%">Nama Barang</th>
                            <th width="8%" class="text-center">Qty</th>
                            <th width="18%" class="text-right">Harga Satuan</th>
                            <th width="19%" class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->serviceBarangItems as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    @if($item->is_manual)
                                        {{ $item->nama_barang_manual }}
                                        
                                    @else
                                        {{ $item->barang ? $item->barang->nama : 'Barang tidak ditemukan' }}
                                        @if($item->barang && ($item->barang->merk || $item->barang->tipe))
                                            <div class="text-xs text-gray-500">{{ $item->barang->merk }} {{ $item->barang->tipe }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->jumlah }} {{ Str::upper($item->satuan) }}</td>
                                <td class="text-right">Rp{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-right"><strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Jasa Items -->
        @if($transaksi->serviceJasaItems->count() > 0)
            <div class="items-section">
                <h3>⚙️ Jasa Service</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Jasa</th>
                            <th width="35%">Keterangan</th>
                            <th width="20%" class="text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->serviceJasaItems as $index => $jasa)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="item-name">{{ $jasa->nama_jasa }}</td>
                                <td>{{ $jasa->keterangan ?: '-' }}</td>
                                <td class="text-right"><strong>Rp{{ number_format($jasa->harga_jasa, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Summary Section with Bank Info -->
        <div class="summary-section">
            <!-- Bank Info & Contact -->
            <div class="bank-contact-info">
                <div class="bank-info">
                    <h3>🏦 Informasi Rekening</h3>
                    
                    <div class="bank-item">
                        <div class="bank-name">Bank Central Asia (BCA)</div>
                        <div class="account-info">Nomor Rekening:</div>
                        <div class="account-number">0511766388</div>
                        <div class="account-info">A/N: Muhammad Taufik Ramadhan</div>
                    </div>
                    
                    <div class="bank-item">
                        <div class="bank-name">Bank BPD Banjarmasin</div>
                        <div class="account-info">Nomor Rekening:</div>
                        <div class="account-number">001 03 01 14240 8</div>
                        <div class="account-info">A/N: Muhammad Taufik Ramadhan</div>
                        <div class="account-info">NPWP: 80.584.552.6.731.000</div>
                    </div>
                </div>
                
                <div class="notes-section">
                    <div>
                        <strong>Note:</strong>
                        <em>Lembar 1: Customer | Lembar 2: Arsip | Lembar 3: Bag. Keuangan</em><br>
                        <em>Spare part yang diganti sudah diterima</em>
                    </div>
                </div>
            </div>
            
            <!-- Summary -->
            <div class="summary">
                <div class="summary-row">
                    <span>Total Barang:</span>
                    <span>Rp{{ number_format($transaksi->total_barang, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Total Jasa:</span>
                    <span>Rp{{ number_format($transaksi->total_jasa, 0, ',', '.') }}</span>
                </div>
                
                <!-- Discount Information -->
                @if($transaksi->diskon > 0)
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>Rp{{ number_format($transaksi->total_barang + $transaksi->total_jasa, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Diskon ({{ $transaksi->tipe_diskon == 'persentase' ? $transaksi->diskon.'%' : 'Rp'.number_format($transaksi->diskon, 0, ',', '.') }}):</span>
                        <span style="color: #dc2626;">-Rp{{ number_format(
                            $transaksi->tipe_diskon == 'persentase' 
                                ? ($transaksi->total_barang + $transaksi->total_jasa) * ($transaksi->diskon / 100)
                                : $transaksi->diskon, 
                            0, ',', '.'
                        ) }}</span>
                    </div>
                @endif
                
                <div class="summary-row summary-total">
                    <span>TOTAL KESELURUHAN:</span>
                    <span>Rp{{ number_format($transaksi->total_keseluruhan, 0, ',', '.') }}</span>
                </div>
                
                <!-- Payment Info -->
                <div class="payment-info">
                    <div class="payment-row">
                        <span>Metode Pembayaran:</span>
                        <span style="text-transform: capitalize;"><strong>{{ $transaksi->metode_pembayaran }}</strong></span>
                    </div>
                    <div class="payment-row">
                        <span>Status Pembayaran:</span>
                        <span class="status-{{ $transaksi->status_pembayaran }}" style="text-transform: uppercase; font-weight: bold;">
                            {{ $transaksi->status_pembayaran }}
                        </span>
                    </div>
                    <div class="payment-row">
                        <span>Sudah Dibayar:</span>
                        <span><strong>Rp{{ number_format($transaksi->total_sudah_dibayar, 0, ',', '.') }}</strong></span>
                    </div>
                    @if($transaksi->sisa_pembayaran > 0)
                        <div class="payment-row">
                            <span>Sisa Pembayaran:</span>
                            <span class="status-piutang"><strong>Rp{{ number_format($transaksi->sisa_pembayaran, 0, ',', '.') }}</strong></span>
                        </div>
                        @if($transaksi->jatuh_tempo)
                            <div class="payment-row">
                                <span>Jatuh Tempo:</span>
                                <span><strong>{{ \Carbon\Carbon::parse($transaksi->jatuh_tempo)->format('d F Y') }}</strong></span>
                            </div>
                        @endif
                    @endif
                    @if($transaksi->keterangan_pembayaran)
                        <div class="payment-row" style="border-top: 1px solid #e5e7eb; padding-top: 6px; margin-top: 6px;">
                            <span>Keterangan:</span>
                            <span><strong>{{ $transaksi->keterangan_pembayaran }}</strong></span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Hormat Kami</div>
                <div class="signature-line"></div>
                <div class="signature-name">( ................................. )</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Kasir:</strong> {{ $transaksi->kasir }}</p>
            <p>Terima kasih atas kepercayaan Anda menggunakan jasa kami.</p>
            <p>Dicetak pada: {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y, H:i') }} WITA</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
