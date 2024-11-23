<?php

namespace App\Exports;

use App\Models\Car;
use App\Models\Contract;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Report implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    protected $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function headings(): array
    {
        return [
            'Bezeichnung',
            'Stammnummer',
            'Einkaufsdatum',
            'Einkaufpreis',
            'Verkäufer',
            'Verkaufsdatum',
            'Verkaufspreis',
            'Käufer',
            'Lagerbestand',
        ];
    }

    public function collection()
    {
        $cars = Contract::with(['car' => function($query) {
            $query->whereNull('deleted_at');
        }])->soldByYear($this->year)->get()->pluck('car');
        $cars = $cars->merge(Car::unsoldOnlyByYear($this->year)->get())->unique()->map(function ($car) {
            $bcontract = $car->latestBuyContractUpToYear($this->year);
            $scontract = $car->latestSellContractUpToYear($this->year);
            if (! $car->isSold()) {
                $scontract = null;
            } elseif ($scontract && $bcontract && $scontract->date < $bcontract->date) {
                $scontract = null;
            }

            return [
                'title' => $car->name_with_year,
                'stammnummer' => $car->stammnummer,
                'buy_date' => $bcontract ? Date::dateTimeToExcel(Carbon::parse($bcontract->date)) : null,
                'buy_price' => $bcontract ? $bcontract->price_for_excel : null,
                'seller' => $bcontract ? $bcontract->contact->full_title_with_address : null,
                'sell_date' => $scontract ? Date::dateTimeToExcel(Carbon::parse($scontract->date)) : null,
                'sell_price' => $scontract ? $scontract->price_for_excel : null,
                'buyer' => $scontract ? $scontract->contact->full_title_with_address : null,
                'lager' => ! $scontract && $bcontract ? $bcontract->price_for_excel : null,
            ];
        })->sortBy('buy_date');

        return $cars;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function title(): string
    {
        return 'Wagenhandelbuch '.$this->year;
    }
}
