<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ContractsExport implements WithMultipleSheets
{
    protected $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new ContractsSheet($this->year);
        $sheets[] = new BuyContractsSheet($this->year);
        $sheets[] = new SellContractsSheet($this->year);

        return $sheets;
    }
}
