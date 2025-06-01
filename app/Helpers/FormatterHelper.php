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

    /**
     * Melakukan konversi waktu menggunakan dateTime
     * @param string $format
     * @param string|bool
     */
    public static function formatDateTime(string $format, ?string $timeString = null): string
    {
        if ($timeString === null) {
            return '-';
        }

        $timeObj = new \DateTime($timeString);

        $currentFormat = $format ?? config('values.date_format_with_hour');
        return $timeObj->format($currentFormat);
    }
}
