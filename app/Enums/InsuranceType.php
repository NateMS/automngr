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
    const QBase = 0;
    const OneStar = 1;
    const ThreeStar = 2;
    const FiveStar = 3;
    const FiveStarPlus = 4;
}
