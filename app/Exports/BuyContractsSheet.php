<?php

namespace App\Exports;

use App\Models\Contract;

class BuyContractsSheet extends ContractsSheet
{

    public function query()
    {
        return Contract::buyContracts()->whereYear('date', '=', $this->year)->orderBy('date', 'asc');
    }

    public function headings(): array
    {
        return  [
            ['Ankaufsverträge ' . $this->year],
            [
                'Datum',
                'Auto',
                'Stammnummer',
                'Verkäufer',
                'Betrag',
            ]
        ];
    }

    public function map($contract): array
    {
        return [
            $contract->date_formatted,
            $contract->car->name,
            $contract->car->stammnummer,
            $contract->contact->full_title,
            $contract->price,
        ];
    }

    public function title(): string
    {
        return 'Ankaufsverträge';
    }
}
