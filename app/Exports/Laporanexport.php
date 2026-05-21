<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected array $rows;
    protected array $headers;
    protected string $judul;
    protected array $moneyColumns;

    public function __construct(array $rows, array $headers, string $judul, array $moneyColumns = [])
    {
        $this->rows         = $rows;
        $this->headers      = $headers;
        $this->judul        = $judul;
        $this->moneyColumns = $moneyColumns;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return 'Laporan';
    }

    public function columnFormats(): array
    {
        $formats = [];
        foreach ($this->moneyColumns as $col) {
            $formats[$col] = '#,##0';
        }
        return $formats;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF6D28D9'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}