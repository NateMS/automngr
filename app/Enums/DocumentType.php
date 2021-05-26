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
    const ContractUnsigned =   0;
    const ContractSigned =   1;
    const Other = 3;
}
