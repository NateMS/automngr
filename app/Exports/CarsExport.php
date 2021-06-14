<?php

namespace App\Exports;

use App\Models\Car;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CarsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $car;
    protected $headings;

    public function __construct($car, $headings)
    {
        $this->car = $car;
        $this->headings = $headings;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    // public function map($car): array
    // {
    //     $contract = $car->latestBuyContract();

    //     return [
    //         $car->name,
    //         $car->stammnummer,
    //         $car->initial_date_formatted,
    //         $contract ? $contract->date_formatted : null,
    //         $contract ? $contract->price : null,
    //     ];
    // }

    public function collection()
    {
        return $this->car;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
