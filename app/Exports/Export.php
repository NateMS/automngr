<?php

namespace App\Exports;

use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Export implements FromCollection, WithHeadings, WithStyles
{
    protected $model;

    protected $headings;

    public function __construct($model, $headings)
    {
        $this->model = $model;
        $this->headings = $headings;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function collection(): Enumerable
    {
        return $this->model;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
