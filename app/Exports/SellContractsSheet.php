<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Events\AfterSheet;

class SellContractsSheet extends ContractsSheet
{

    public function query()
    {
        return Contract::sellContracts()->whereYear('date', '=', $this->year)->orderBy('date', 'asc');
    }

    public function headings(): array
    {
        return [
            ['Verkaufsverträge ' . $this->year],
            [
                'Datum',
                'Auto',
                'Stammnummer',
                'Käufer',
                'Versicherung',
                'Betrag',
                'Eingezahlt',
                'Noch offen'
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
            $contract->insurance_type_formatted,
            $contract->price,
            $contract->paid,
            $contract->left_to_pay,
        ];
    }

    public function title(): string
    {
        return 'Verkaufsverträge';
    }
}
