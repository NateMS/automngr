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
    const Keine = '0';

    const QBase = '1';

    const OneStar = '2';

    const ThreeStar = '3';

    const FiveStar = '4';

    const FiveStarPlus = '5';
}
