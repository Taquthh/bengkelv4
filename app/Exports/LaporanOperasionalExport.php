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

class LaporanOperasionalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
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
            'kategori' => '',
            'deskripsi' => 'TOTAL PENGELUARAN',
            'jumlah' => $this->summary['total_operasional']
        ]);

        return $collection;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN OPERASIONAL'],
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
        
        if ($row['deskripsi'] === 'TOTAL PENGELUARAN') {
            return [
                '',
                '',
                $row['deskripsi'],
                'Rp ' . number_format($row['jumlah'], 0, ',', '.')
            ];
        }
        
        $counter++;
        return [
            $counter,
            $row['tanggal'],
            $row['deskripsi'], // Item name
            'Rp ' . number_format($row['jumlah'], 0, ',', '.')
        ];
    }

    public function title(): string
    {
        return 'Laporan Operasional';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling - merge cells across columns A to D
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '000000']], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']], 'alignment' => ['horizontal' => 'center']],
            3 => ['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']], 'alignment' => ['horizontal' => 'center']],
            4 => ['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']], 'alignment' => ['horizontal' => 'center']],
            6 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E5E7EB']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set specific column widths - 4 columns only
                $sheet->getColumnDimension('A')->setWidth(8);   // NO column
                $sheet->getColumnDimension('B')->setWidth(12);  // TGL column
                $sheet->getColumnDimension('C')->setWidth(35);  // ITEM column
                $sheet->getColumnDimension('D')->setWidth(15);  // JUMLAH column

                // Format numbers in currency columns - right aligned
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('D7:D'.$highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
                
                $sheet->getStyle('D7:D'.$highestRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Center align NO column
                $sheet->getStyle('A7:A'.($highestRow-1))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Center align TGL column  
                $sheet->getStyle('B7:B'.($highestRow-1))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Style the total row - gray background
                $totalRow = $highestRow;
                $sheet->getStyle('A'.$totalRow.':D'.$totalRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '6B7280']  // Gray color
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Right align the total amount
                $sheet->getStyle('D'.$totalRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Add borders to data table including headers
                $sheet->getStyle('A6:D'.$highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ],
                    ],
                ]);

                // Add dotted lines between rows
                for ($row = 7; $row < $totalRow; $row++) {
                    $sheet->getStyle('A'.$row.':D'.$row)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_DOTTED,
                                'color' => ['rgb' => '000000']
                            ],
                        ],
                    ]);
                }

                // Header row background color (light gray)
                $sheet->getStyle('A6:D6')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'E5E7EB']  // Light gray
                    ],
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            },
        ];
    }
}