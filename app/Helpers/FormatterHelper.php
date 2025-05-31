<?php

namespace App\Helpers;

class FormatterHelper
{
    /**
     * Format price to IDR
     * @param int $price
     * @return string
     */
    public static function formatPrice(int $price): string
    {
        return number_format($price, 0, ',', '.');
    }
}
