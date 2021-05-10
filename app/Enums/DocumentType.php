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
final class DocumentType extends Enum
{
    const BuyContractUnsigned =   0;
    const BuyContractSigned =   1;
    const SellContractUnSigned = 2;
    const SellContractSigned = 3;
    const Other = 4;
}
