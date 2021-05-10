<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 * @method static static OptionFour()
 * @method static static OptionFive()
 */
final class InsuranceType extends Enum
{
    const OptionOne =   0;
    const OptionTwo =   1;
    const OptionThree = 2;
    const OptionFour = 3;
    const OptionFive = 4;
}
