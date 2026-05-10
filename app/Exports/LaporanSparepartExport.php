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

class LaporanSparepartExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
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
        // Add summary row at the end
        $collection = collect($this->data)->push([
            'tanggal' => '',
            'item' => 'TOTAL PENGELUARAN SPARE PART',
            'jumlah' => $this->summary['total_pengeluaran']
        ]);

        return $collection;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENGELUARAN SPARE PART'],
            ['BENGKEL FIRDAUS JAYA SENTOSA'],
            ['PERIODE ' . Carbon::parse($this->config['weekStart'])->format('d') . ' - ' . Carbon::parse($this->config['weekEnd'])->format('d') . ' ' . Carbon::parse($this->config['weekEnd'])->translatedFormat('F Y')],
            ['MINGGU KE-' . $this->config['weekNumber']],
            [''], // Empty row
            ['NO', 'TGL', 'ITEM', 'JUMLAH']
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        
        if ($row['item'] === 'TOTAL PENGELUARAN SPARE PART') {
            return [
                '',
                '',
                $row['item'],
                $row['jumlah']
            ];
        }
        
        $counter++;
        return [
            $counter,
            $row['tanggal'],
            $row['item'],
            $row['jumlah']
        ];
    }

    public function title(): string
    {
        return 'Laporan Sparepart';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling - merge cells across columns A to D
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');

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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set row heights
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(18);
                $sheet->getRowDimension(6)->setRowHeight(30);
                
                // Set specific column widths - 4 columns only
                $sheet->getColumnDimension('A')->setWidth(8);   // NO column
                $sheet->getColumnDimension('B')->setWidth(12);  // TGL column
                $sheet->getColumnDimension('C')->setWidth(35);  // ITEM column
                $sheet->getColumnDimension('D')->setWidth(20);  // JUMLAH column

                // Format numbers in currency columns
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('D7:D'.$highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');
                
                $sheet->getStyle('D7:D'.$highestRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Style data rows with gray alternating colors
                for ($row = 7; $row < $highestRow; $row++) {
                    $bgColor = ($row % 2 == 0) ? 'F9FAFB' : 'FFFFFF';
                    $sheet->getStyle('A'.$row.':D'.$row)->applyFromArray([
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

                // Center align NO column
                $sheet->getStyle('A7:A'.($highestRow-1))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Center align TGL column  
                $sheet->getStyle('B7:B'.($highestRow-1))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Style the total row
                $totalRow = $highestRow;
                $sheet->getStyle('A'.$totalRow.':D'.$totalRow)->applyFromArray([
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

                // Right align the total amount
                $sheet->getStyle('D'.$totalRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                
                $sheet->getRowDimension($totalRow)->setRowHeight(24);
            },
        ];
    }
}   