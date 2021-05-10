<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Transaction()
 * @method static static Cash()
 */
final class PaymentType extends Enum
{
    const Transaction = 0;
    const Cash = 1;
}
