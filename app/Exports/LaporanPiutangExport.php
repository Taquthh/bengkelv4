<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LaporanPiutangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $piutangDetail;
    protected $config;

    public function __construct($piutangDetail, $config)
    {
        $this->piutangDetail = $piutangDetail;
        $this->config = $config;
    }

    public function collection()
    {
        return collect($this->piutangDetail);
    }

    public function headings(): array
    {
        $monthYear = Carbon::create($this->config['year'], $this->config['month'], 1)->translatedFormat('F Y');
        $startDate = Carbon::parse($this->config['startDate'])->format('d');
        $endDate = Carbon::parse($this->config['endDate'])->format('d M Y');
        
        return [
            ['LAPORAN PIUTANG'],
            ['BENGKEL FIRDAUS JAYA SENTOSA'],
            ['PERIODE ' . $startDate . ' - ' . $endDate . ' ' . strtoupper($monthYear)],
            [''], // Empty row
            ['No', 'Tanggal', 'No.Invoice', 'Merk/No.Pol', 'Laporan', 'Tagihan', 'Keterangan']
        ];
    }

    public function map($row): array
    {
        $keterangan = $row['lunas'] 
            ? 'LUNAS - ' . Carbon::parse($row['tanggal_lunas'])->format('d M Y')
            : $row['keterangan'];
            
        return [
            $row['no'],
            $row['tanggal'],
            $row['invoice'],
            $row['merk_nopol'],
            $row['laporan'],
            $row['tagihan'],
            $keterangan
        ];
    }

    public function title(): string
    {
        return 'Laporan Piutang';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');

        return [
            // Main title
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '000000']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Company name
            2 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Period
            3 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Table headers
            5 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E5E7EB']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']],
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set row heights
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(5)->setRowHeight(30);

                // Column widths
                $columnWidths = [
                    'A' => 6,   // No
                    'B' => 12,  // Tanggal
                    'C' => 20,  // Invoice
                    'D' => 25,  // Merk/No.Pol
                    'E' => 30,  // Laporan
                    'F' => 18,  // Tagihan
                    'G' => 25   // Keterangan
                ];

                foreach($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Format currency column
                $dataStartRow = 6;
                $dataEndRow = $dataStartRow + count($this->piutangDetail) - 1;
                
                if (count($this->piutangDetail) > 0) {
                    $sheet->getStyle('F'.$dataStartRow.':F'.$dataEndRow)
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                }

                // Style data rows with gray alternating colors
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    $bgColor = ($row % 2 == 0) ? 'F9FAFB' : 'FFFFFF';
                    $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => $bgColor]
                        ],
                        'alignment' => ['vertical' => 'center'],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D1D5DB']
                            ]
                        ],
                        'font' => ['size' => 10, 'color' => ['rgb' => '374151']]
                    ]);
                    
                    // Right align tagihan column
                    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal('right');
                    $sheet->getRowDimension($row)->setRowHeight(20);
                }

                // Total row - Gray theme
                if (count($this->piutangDetail) > 0) {
                    $totalRow = $dataEndRow + 1;
                    
                    // Calculate totals
                    $totalTagihan = collect($this->piutangDetail)->sum('tagihan');
                    $sisaTagihan = collect($this->piutangDetail)->sum('sisa');
                    
                    $sheet->setCellValue('A'.$totalRow, 'TOTAL');
                    $sheet->mergeCells('A'.$totalRow.':E'.$totalRow);
                    $sheet->setCellValue('F'.$totalRow, $totalTagihan);
                    $sheet->setCellValue('G'.$totalRow, 'SISA TAGIHAN Rp ' . number_format($sisaTagihan, 0, ',', '.'));

                    $sheet->getStyle('A'.$totalRow.':G'.$totalRow)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => '4B5563']
                        ],
                        'alignment' => ['vertical' => 'center'],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['rgb' => '374151']
                            ]
                        ]
                    ]);

                    // Format currency in total row
                    $sheet->getStyle('F'.$totalRow)
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle('F'.$totalRow)->getAlignment()->setHorizontal('right');
                    
                    $sheet->getRowDimension($totalRow)->setRowHeight(24);

                    // Summary section - COMPACT LAYOUT
                    $summaryStartRow = $totalRow + 2;
                    
                    $sheet->setCellValue('B'.$summaryStartRow, 'RINGKASAN PIUTANG');
                    $sheet->mergeCells('B'.$summaryStartRow.':G'.$summaryStartRow);
                    $sheet->getStyle('B'.$summaryStartRow.':G'.$summaryStartRow)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1F2937']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D1D5DB']],
                        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]]
                    ]);
                    $sheet->getRowDimension($summaryStartRow)->setRowHeight(24);

                    $currentRow = $summaryStartRow + 1;
                    
                    // Compact table format for financial summary
                    $jumlahLunas = collect($this->piutangDetail)->where('lunas', true)->count();
                    $jumlahBelumLunas = collect($this->piutangDetail)->where('lunas', false)->count();
                    
                    $summaryItems = [
                        ['Total Piutang', $totalTagihan, true],
                        ['Sisa Tagihan', $sisaTagihan, true],
                        ['Invoice Lunas', $jumlahLunas . ' invoice', false],
                        ['Invoice Belum Lunas', $jumlahBelumLunas . ' invoice', false],
                    ];

                    foreach ($summaryItems as $index => $item) {
                        $isCurrency = $item[2];
                        $bgColor = ($index < 2) ? ($index % 2 == 0 ? 'F3F4F6' : 'E5E7EB') : 'F9FAFB';
                        $isBold = ($index < 2);
                        
                        $sheet->setCellValue('B'.$currentRow, $item[0]);
                        $sheet->setCellValue('F'.$currentRow, $item[1]);
                        $sheet->mergeCells('B'.$currentRow.':E'.$currentRow);
                        
                        $sheet->getStyle('B'.$currentRow.':F'.$currentRow)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $bgColor]],
                            'font' => ['bold' => $isBold, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                            'alignment' => ['vertical' => 'center'],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]]
                        ]);
                        $sheet->getStyle('F'.$currentRow)->getAlignment()->setHorizontal('right');
                        
                        if ($isCurrency) {
                            $sheet->getStyle('F'.$currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
                        }
                        
                        $sheet->getRowDimension($currentRow)->setRowHeight(22);
                        $currentRow++;
                    }
                }
            },
        ];
    }
}