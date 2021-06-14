<?php

namespace App\Exports;

use App\Exports\CarsExport;

class UnsoldCarsExport extends CarsExport
{
    public function headings(): array
    {
        return  [
            'Name',
            'Stammnummer',
            'Inverkehrssetzung',
            'Einkaufsdatum',
            'Einkaufspreis',
        ];
    }

    public function map($car): array
    {
        $contract = $car->latestBuyContract();

        return [
            $car->name,
            $car->stammnummer,
            $car->initial_date_formatted,
            $contract ? $contract->date_formatted : null,
            $contract ? $contract->price : null,
        ];
    }
}
