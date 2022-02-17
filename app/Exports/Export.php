<?php

namespace App\Exports;

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

    public function collection()
    {
        return $this->model;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
