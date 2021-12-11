<?php

namespace App\traits;

trait SimpleTokenGeneratorTrait
{

    public static function generateToken($num = 6): string
    {
        $minimum = 1 * 10 ** ($num - 1);
        $maximum = ($minimum * 10) - 1;
        return mt_rand($minimum, $maximum);
    }
}
