<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CONNECTED()
 * @method static static UPDATED()
 * @method static static REAUTHORIZATION()
 * @method static static PAYMENT()
 */
final class MonoEventType extends Enum
{
    const CONNECTED =   "mono.events.account_connected";
    const UPDATED =   "mono.events.account_updated";
    const REAUTHORIZATION =   "mono.events.reauthorisation_required";
    const PAYMENT =   "mono_payment.payment_successful";
}
