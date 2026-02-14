<?php

namespace App\Http\Traits;

use App\Models\Contract;

trait MapsContractFields
{
    private function getContractFields(?Contract $contract): ?array
    {
        if (! $contract) {
            return null;
        }

        return [
            'id' => $contract->id,
            'date' => $contract->date_formatted,
            'price' => $contract->price->format(),
            'contact' => $contract->contact->full_title,
            'car' => $contract->car->name_with_year,
            'link' => route('contracts.show', $contract),
        ];
    }
}
