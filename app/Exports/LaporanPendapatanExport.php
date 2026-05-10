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

class LaporanPendapatanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $data;
    protected $summary;
    protected $config;

    public function __construct($data, $summary, $config)
    {
        $this->data = $data;
        $this->summary = $summary;
        $this->config = $config;
    }

    public function collection()
    {
        // Add summary rows at the end
        $collection = collect($this->data)->push([
            'tanggal' => '',
            'item' => 'TOTAL PENDAPATAN BRUTO',
            'jumlah' => $this->summary['total_pendapatan_bruto'],
            'discount' => $this->summary['total_discount'],
            'modal' => $this->summary['total_modal'],
            'jasa' => $this->summary['total_jasa'],
            'laba_spart' => $this->summary['total_laba_spart'],
            'invoice' => ''
        ]);

        return $collection;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENDAPATAN'],
            ['BENGKEL FIRDAUS JAYA SENTOSA'],
            ['PERIODE ' . Carbon::parse($this->config['weekStart'])->format('d') . ' - ' . Carbon::parse($this->config['weekEnd'])->format('d') . ' ' . Carbon::parse($this->config['weekEnd'])->translatedFormat('F Y')],
            ['MINGGU KE-' . $this->config['weekNumber']],
            [''], // Empty row
            ['NO', 'TGL', 'ITEM', 'JUMLAH', 'DISCOUNT', 'MODAL', 'JASA', 'LABA S.PART']
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        
        if ($row['item'] === 'TOTAL PENDAPATAN BRUTO') {
            return [
                '',
                '',
                $row['item'],
                $row['jumlah'],
                $row['discount'],
                $row['modal'],
                $row['jasa'],
                $row['laba_spart']
            ];
        }
        
        $counter++;
        return [
            $counter,
            $row['tanggal'],
            $row['item'],
            $row['jumlah'],
            $row['discount'],
            $row['modal'],
            $row['jasa'],
            $row['laba_spart']
        ];
    }

    public function title(): string
    {
        return 'Laporan Pendapatan';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling - merge cells for title sections
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        $sheet->mergeCells('A4:H4');

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
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Week number
            4 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Table headers
            6 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E5E7EB']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']],
                ]
            ],
        ];
    }

    private function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set row heights for header section
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(18);
                $sheet->getRowDimension(6)->setRowHeight(30);

                // Auto-size columns with appropriate widths for large numbers
                $columnWidths = [
                    'A' => 6,   // NO
                    'B' => 12,  // TGL
                    'C' => 35,  // ITEM - lebih lebar untuk deskripsi panjang
                    'D' => 20,  // JUMLAH - lebih lebar untuk puluhan juta
                    'E' => 18,  // DISCOUNT
                    'F' => 18,  // MODAL
                    'G' => 18,  // JASA
                    'H' => 20   // LABA S.PART - lebih lebar untuk puluhan juta
                ];

                foreach($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Format numbers in currency columns
                $highestRow = $sheet->getHighestRow();
                $currencyColumns = ['D', 'E', 'F', 'G', 'H'];
                
                foreach ($currencyColumns as $col) {
                    $sheet->getStyle($col.'7:'.$col.$highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                }

                // Style data rows with alternating colors
                for ($row = 7; $row < $highestRow; $row++) {
                    $bgColor = ($row % 2 == 0) ? 'F9FAFB' : 'FFFFFF';
                    $sheet->getStyle('A'.$row.':H'.$row)->applyFromArray([
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
                    $sheet->getRowDimension($row)->setRowHeight(20);
                }

                // Style the total row
                $totalRow = $highestRow;
                $sheet->getStyle('A'.$totalRow.':H'.$totalRow)->applyFromArray([
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
                $sheet->getRowDimension($totalRow)->setRowHeight(24);

                // Calculate positions for the summary section
                $startRow = $highestRow + 2;

                // Left column: RINGKASAN KEUANGAN
                $sheet->setCellValue('A'.$startRow, 'RINGKASAN KEUANGAN MINGGU KE-' . $this->config['weekNumber']);
                $sheet->mergeCells('A'.$startRow.':D'.$startRow);
                $sheet->getStyle('A'.$startRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1F2937']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D1D5DB']
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]
                    ]
                ]);

                // Right column: DETAIL KOMPONEN PENDAPATAN
                $sheet->setCellValue('E'.$startRow, 'DETAIL KOMPONEN PENDAPATAN');
                $sheet->mergeCells('E'.$startRow.':H'.$startRow);
                $sheet->getStyle('E'.$startRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1F2937']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D1D5DB']
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]
                    ]
                ]);

                $sheet->getRowDimension($startRow)->setRowHeight(24);
                $currentRow = $startRow + 1;

                // Left side - Ringkasan Keuangan dengan warna yang sederhana
                $ringkasanData = [
                    ['Pendapatan Bruto', $this->summary['total_pendapatan_bruto'], 'F3F4F6', '1F2937'],
                    ['Pengeluaran Spare Part', $this->summary['total_modal'], 'E5E7EB', '1F2937'],  
                    ['Total Operasional', $this->summary['operasional'], 'F3F4F6', '1F2937'],
                ];

                foreach ($ringkasanData as $item) {
                    $sheet->setCellValue('A'.$currentRow, $item[0]);
                    $sheet->setCellValue('D'.$currentRow, $this->formatCurrency($item[1]));
                    
                    // Merge cells for better appearance
                    $sheet->mergeCells('A'.$currentRow.':C'.$currentRow);
                    
                    $sheet->getStyle('A'.$currentRow.':D'.$currentRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => $item[2]]
                        ],
                        'font' => ['color' => ['rgb' => $item[3]], 'bold' => true, 'size' => 10],
                        'alignment' => ['horizontal' => 'left', 'vertical' => 'center'],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]
                        ]
                    ]);
                    
                    // Right align currency
                    $sheet->getStyle('D'.$currentRow)->getAlignment()->setHorizontal('right');
                    $sheet->getRowDimension($currentRow)->setRowHeight(22);
                    $currentRow++;
                }

                // Detail Piutang section
                $currentRow += 1;
                $sheet->setCellValue('A'.$currentRow, 'Detail Piutang');
                $sheet->mergeCells('A'.$currentRow.':D'.$currentRow);
                $sheet->getStyle('A'.$currentRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1F2937']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'E5E7EB']
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]
                    ]
                ]);
                $sheet->getRowDimension($currentRow)->setRowHeight(22);
                $currentRow++;

                // Piutang detail items
                $piutangDetail = $this->summary['piutang_per_invoice'] ?? collect();
                foreach ($piutangDetail as $piutang) {
                    $sheet->setCellValue('A'.$currentRow, $piutang->invoice);
                    $sheet->setCellValue('D'.$currentRow, $this->formatCurrency($piutang->total_piutang));
                    $sheet->mergeCells('A'.$currentRow.':C'.$currentRow);
                    
                    $sheet->getStyle('A'.$currentRow.':D'.$currentRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]
                        ],
                        'alignment' => ['vertical' => 'center'],
                        'font' => ['size' => 10, 'color' => ['rgb' => '374151']]
                    ]);
                    $sheet->getStyle('D'.$currentRow)->getAlignment()->setHorizontal('right');
                    $sheet->getRowDimension($currentRow)->setRowHeight(20);
                    $currentRow++;
                }

                // Total Piutang
                $sheet->setCellValue('A'.$currentRow, 'Total Piutang');
                $sheet->setCellValue('D'.$currentRow, $this->formatCurrency($this->summary['total_piutang']));
                $sheet->mergeCells('A'.$currentRow.':C'.$currentRow);
                $sheet->getStyle('A'.$currentRow.':D'.$currentRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D1D5DB']
                    ],
                    'alignment' => ['vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]
                    ]
                ]);
                $sheet->getStyle('D'.$currentRow)->getAlignment()->setHorizontal('right');
                $sheet->getRowDimension($currentRow)->setRowHeight(22);

                // Right side - Detail Komponen Pendapatan
                $detailRow = $startRow + 1;
                
                $komponenData = [
                    ['Jasa', $this->summary['total_jasa'], 'F3F4F6'],
                    ['Laba Spare Part', $this->summary['total_laba_spart'], 'E5E7EB'],
                    ['Discount', $this->summary['total_discount'], 'F3F4F6'],
                ];

                foreach ($komponenData as $komponen) {
                    $sheet->setCellValue('E'.$detailRow, $komponen[0]);
                    $sheet->setCellValue('H'.$detailRow, $this->formatCurrency($komponen[1]));
                    $sheet->mergeCells('E'.$detailRow.':G'.$detailRow);
                    
                    $sheet->getStyle('E'.$detailRow.':H'.$detailRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => $komponen[2]]
                        ],
                        'font' => ['color' => ['rgb' => '1F2937'], 'bold' => true, 'size' => 10],
                        'alignment' => ['vertical' => 'center'],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]
                        ]
                    ]);
                    $sheet->getStyle('H'.$detailRow)->getAlignment()->setHorizontal('right');
                    $sheet->getRowDimension($detailRow)->setRowHeight(22);
                    $detailRow++;
                }

                // Down Payment section
                $detailRow += 1;
                $sheet->setCellValue('E'.$detailRow, 'Down Payment (DP)');
                $sheet->mergeCells('E'.$detailRow.':H'.$detailRow);
                $sheet->getStyle('E'.$detailRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1F2937']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'E5E7EB']
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]
                    ]
                ]);
                $sheet->getRowDimension($detailRow)->setRowHeight(22);
                $detailRow++;

                // DP detail items
                $dpDetail = $this->summary['dp_per_invoice'] ?? collect();
                foreach ($dpDetail as $dp) {
                    $sheet->setCellValue('E'.$detailRow, $dp['invoice']);
                    $sheet->setCellValue('H'.$detailRow, $this->formatCurrency($dp['dp']));
                    $sheet->mergeCells('E'.$detailRow.':G'.$detailRow);
                    
                    $sheet->getStyle('E'.$detailRow.':H'.$detailRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]
                        ],
                        'alignment' => ['vertical' => 'center'],
                        'font' => ['size' => 10, 'color' => ['rgb' => '374151']]
                    ]);
                    $sheet->getStyle('H'.$detailRow)->getAlignment()->setHorizontal('right');
                    $sheet->getRowDimension($detailRow)->setRowHeight(20);
                    $detailRow++;
                }

                // Total DP
                $sheet->setCellValue('E'.$detailRow, 'Total DP');
                $sheet->setCellValue('H'.$detailRow, $this->formatCurrency($this->summary['total_dp']));
                $sheet->mergeCells('E'.$detailRow.':G'.$detailRow);
                $sheet->getStyle('E'.$detailRow.':H'.$detailRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D1D5DB']
                    ],
                    'alignment' => ['vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]
                    ]
                ]);
                $sheet->getStyle('H'.$detailRow)->getAlignment()->setHorizontal('right');
                $sheet->getRowDimension($detailRow)->setRowHeight(22);

                // Final result - Pendapatan Bersih
                $finalRow = max($currentRow, $detailRow) + 2;
                
                $sheet->mergeCells('A'.$finalRow.':H'.$finalRow);
                $pendapatanBersih = $this->summary['pendapatan_bersih'];
                $statusText = $pendapatanBersih >= 0 ? 'PROFIT' : 'LOSS';
                $statusIcon = $pendapatanBersih >= 0 ? '✓' : '✗';
                
                $finalText = 'PENDAPATAN BERSIH MINGGU KE-' . $this->config['weekNumber'] . ' | ' . 
                            'Keuntungan periode ini: ' . $this->formatCurrency($pendapatanBersih) . ' | ' .
                            $statusIcon . ' ' . $statusText;
                
                $sheet->setCellValue('A'.$finalRow, $finalText);
                
                $bgColor = $pendapatanBersih >= 0 ? '10B981' : 'EF4444';
                $sheet->getStyle('A'.$finalRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $bgColor]
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '6B7280']]
                    ]
                ]);
                
                $sheet->getRowDimension($finalRow)->setRowHeight(30);
            },
        ];
    }
}