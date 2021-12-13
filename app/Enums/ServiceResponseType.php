<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUCCESS()
 * @method static static ERROR()
 * @method static static VALIDATION
 */
final class ServiceResponseType extends Enum
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const VALIDATION = 'validation';
}
