<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static BuyContractUnsigned()
 * @method static static BuyContractSigned()
 * @method static static SellContractUnSigned()
 * @method static static SellContractSigned()
 * @method static static Other()
 */
final class ContractType extends Enum
{
    const BuyContract = '0';
    const SellContract = '1';
}
