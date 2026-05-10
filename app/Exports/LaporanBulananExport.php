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

class LaporanBulananExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $weeklySummaries;
    protected $monthlyTotals;
    protected $config;

    public function __construct($weeklySummaries, $monthlyTotals, $config)
    {
        $this->weeklySummaries = $weeklySummaries;
        $this->monthlyTotals = $monthlyTotals;
        $this->config = $config;
    }

    public function collection()
    {
        return collect($this->weeklySummaries);
    }

    public function headings(): array
    {
        $monthYear = Carbon::create($this->config['year'], $this->config['month'], 1)->translatedFormat('F Y');
        $endDay = Carbon::create($this->config['year'], $this->config['month'], 1)->endOfMonth()->format('d');
        
        return [
            ['LAPORAN BULANAN'],
            ['BENGKEL FIRDAUS JAYA SENTOSA'],
            ['PERIODE 01-' . $endDay . ' ' . strtoupper($monthYear)],
            [''], // Empty row
            ['Pendapatan per Minggu "FJS"', 'Pendapatan Kotor', 'Operasional', 'Jasa', 'Laba Spre Part', 'Discount', 'DP', 'Piutang', 'Pendapatan Bersih']
        ];
    }

    public function map($row): array
    {
        $weekLabel = '* MINGGU ' . $row['week'] . ' : (' . 
                     Carbon::parse($row['start'])->format('d M') . ' - ' . 
                     Carbon::parse($row['end'])->format('d M') . ')';
        
        return [
            $weekLabel,
            $row['pendapatan_kotor'],
            $row['operasional'],
            $row['jasa'],
            $row['laba_spart'],
            $row['discount'],
            $row['dp'],
            $row['piutang'],
            $row['pendapatan_bersih']
        ];
    }

    public function title(): string
    {
        return 'Laporan Bulanan';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

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
                    'A' => 32, // Minggu label
                    'B' => 16, // Pendapatan Kotor
                    'C' => 16, // Operasional
                    'D' => 14, // Jasa
                    'E' => 16, // Laba Spart
                    'F' => 14, // Discount
                    'G' => 12, // DP
                    'H' => 14, // Piutang
                    'I' => 18  // Pendapatan Bersih
                ];

                foreach($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Format numbers in currency columns
                $dataStartRow = 6;
                $dataEndRow = $dataStartRow + count($this->weeklySummaries) - 1;
                $currencyColumns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
                
                foreach ($currencyColumns as $col) {
                    if (count($this->weeklySummaries) > 0) {
                        $sheet->getStyle($col.$dataStartRow.':'.$col.$dataEndRow)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0');
                    }
                }

                // Style data rows with gray alternating colors
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    $bgColor = ($row % 2 == 0) ? 'F9FAFB' : 'FFFFFF';
                    $sheet->getStyle('A'.$row.':I'.$row)->applyFromArray([
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
                    
                    // Right align numeric columns
                    foreach ($currencyColumns as $col) {
                        $sheet->getStyle($col.$row)->getAlignment()->setHorizontal('right');
                    }
                    
                    $sheet->getRowDimension($row)->setRowHeight(20);
                }

                // Total row - Gray theme
                $totalRow = $dataEndRow + 1;
                $sheet->setCellValue('A'.$totalRow, 'TOTAL');
                $sheet->setCellValue('B'.$totalRow, $this->monthlyTotals['total_pendapatan_kotor']);
                $sheet->setCellValue('C'.$totalRow, $this->monthlyTotals['total_operasional']);
                $sheet->setCellValue('D'.$totalRow, $this->monthlyTotals['total_jasa']);
                $sheet->setCellValue('E'.$totalRow, $this->monthlyTotals['total_laba_spart']);
                $sheet->setCellValue('F'.$totalRow, $this->monthlyTotals['total_discount']);
                $sheet->setCellValue('G'.$totalRow, $this->monthlyTotals['total_dp']);
                $sheet->setCellValue('H'.$totalRow, $this->monthlyTotals['total_piutang']);
                $sheet->setCellValue('I'.$totalRow, $this->monthlyTotals['total_pendapatan_bersih']);

                $sheet->getStyle('A'.$totalRow.':I'.$totalRow)->applyFromArray([
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
                foreach ($currencyColumns as $col) {
                    $sheet->getStyle($col.$totalRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                    $sheet->getStyle($col.$totalRow)->getAlignment()->setHorizontal('right');
                }
                
                $sheet->getRowDimension($totalRow)->setRowHeight(24);

                // Summary section - COMPACT LAYOUT
                $summaryStartRow = $totalRow + 2;
                $monthYear = Carbon::create($this->config['year'], $this->config['month'], 1)->translatedFormat('F Y');

                // Section title
                $sheet->setCellValue('B'.$summaryStartRow, 'RINGKASAN KEUANGAN BULANAN');
                $sheet->mergeCells('B'.$summaryStartRow.':I'.$summaryStartRow);
                $sheet->getStyle('B'.$summaryStartRow.':I'.$summaryStartRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1F2937']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D1D5DB']],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]]
                ]);
                $sheet->getRowDimension($summaryStartRow)->setRowHeight(24);

                $currentRow = $summaryStartRow + 1;
                
                // Summary items in compact table format
                $summaryItems = [
                    ['TOTAL PENDAPATAN KOTOR ' . strtoupper($monthYear), $this->monthlyTotals['total_pendapatan_kotor'], 'F3F4F6', false],
                    ['TOTAL PENGELUARAN ' . strtoupper($monthYear), $this->monthlyTotals['total_operasional'], 'E5E7EB', false],
                    ['TOTAL PIUTANG - DP', $this->monthlyTotals['total_piutang_dp'], 'F3F4F6', false],
                    ['TOTAL PENDAPATAN BERSIH', $this->monthlyTotals['total_pendapatan_bersih'], '374151', true],
                ];

                foreach ($summaryItems as $item) {
                    $isHighlight = $item[3];
                    $fontSize = $isHighlight ? 11 : 10;
                    $fontColor = $isHighlight ? 'FFFFFF' : '1F2937';
                    $borderStyle = $isHighlight ? Border::BORDER_MEDIUM : Border::BORDER_THIN;
                    $rowHeight = $isHighlight ? 26 : 22;
                    
                    $sheet->setCellValue('B'.$currentRow, $item[0]);
                    $sheet->setCellValue('H'.$currentRow, $item[1]);
                    $sheet->mergeCells('B'.$currentRow.':G'.$currentRow);
                    $sheet->mergeCells('H'.$currentRow.':I'.$currentRow);
                    
                    $sheet->getStyle('B'.$currentRow.':I'.$currentRow)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $item[2]]],
                        'font' => ['color' => ['rgb' => $fontColor], 'bold' => true, 'size' => $fontSize],
                        'alignment' => ['vertical' => 'center'],
                        'borders' => ['allBorders' => ['borderStyle' => $borderStyle, 'color' => ['rgb' => '9CA3AF']]]
                    ]);
                    
                    $sheet->getStyle('H'.$currentRow.':I'.$currentRow)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('H'.$currentRow.':I'.$currentRow)->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight);
                    $currentRow++;
                }

                $currentRow++;
                
                // Additional summary boxes in 2 columns side by side
                // Left box: Pendapatan Bersih Bengkel
                $sheet->setCellValue('B'.$currentRow, 'PENDAPATAN BERSIH BENGKEL "FJS"');
                $sheet->setCellValue('E'.$currentRow, $this->monthlyTotals['total_pendapatan_bersih']);
                $sheet->mergeCells('B'.$currentRow.':D'.$currentRow);
                
                $sheet->getStyle('B'.$currentRow.':E'.$currentRow)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E5E7EB']],
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['vertical' => 'center'],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]]
                ]);
                $sheet->getStyle('E'.$currentRow)->getAlignment()->setHorizontal('right');
                $sheet->getStyle('E'.$currentRow)->getNumberFormat()->setFormatCode('#,##0');
                
                // Right box: Pendapatan Bersih Periode
                $sheet->setCellValue('F'.$currentRow, 'PENDAPATAN BERSIH ' . strtoupper($monthYear));
                $sheet->setCellValue('I'.$currentRow, $this->monthlyTotals['total_pendapatan_bersih']);
                $sheet->mergeCells('F'.$currentRow.':H'.$currentRow);
                
                $sheet->getStyle('F'.$currentRow.':I'.$currentRow)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D1D5DB']],
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['vertical' => 'center'],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '9CA3AF']]]
                ]);
                $sheet->getStyle('I'.$currentRow)->getAlignment()->setHorizontal('right');
                $sheet->getStyle('I'.$currentRow)->getNumberFormat()->setFormatCode('#,##0');
                
                $sheet->getRowDimension($currentRow)->setRowHeight(24);
            },
        ];
    }
}