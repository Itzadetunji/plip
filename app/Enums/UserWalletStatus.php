<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static AVAILABLE()
 * @method static static NOT_AVAILABLE()
 * @method static static OptionThree()
 */
final class UserWalletStatus extends Enum
{
    const AVAILABLE =   "AVAILABLE";
    const NOT_AVAILABLE =   "NOT_AVAILABLE";
}
