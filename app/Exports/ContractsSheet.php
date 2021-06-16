<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class ContractsSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, WithEvents
{
    use RegistersEventListeners;
    protected $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function query()
    {
        return Contract::whereYear('date', '=', $this->year)->orderBy('date', 'asc');
    }

    public function headings(): array
    {
        return  [
            ['Alle Verträge ' . $this->year],
            [
                'Datum',
                'Vertragsart',
                'Auto',
                'Stammnummer',
                'Kontakt',
                'Betrag',
            ]
        ];
    }

    public function map($contract): array
    {
        return [
            $contract->date_formatted,
            $contract->type_formatted,
            $contract->car->name,
            $contract->car->stammnummer,
            $contract->contact->full_title,
            $contract->price,
        ];
    }

    public function title(): string
    {
        return 'Alle Verträge';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
