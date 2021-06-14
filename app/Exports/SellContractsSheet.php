<?php

namespace App\Exports;

use App\Models\Contract;
use App\Enums\ContractType;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SellContractsSheet extends ContractsSheet
{

    public function query()
    {
        return Contract::where('type', ContractType::SellContract)->whereYear('date', '=', $this->year)->orderBy('date', 'asc');
    }

    public function headings(): array
    {
        return [
            ['Verkaufverträge ' . $this->year],
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
        return 'Verkaufverträge';
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->appendRows([
            [null, null, null, null, 'Total', 1112, 100, 1002],
        ], $event);
    }
}
