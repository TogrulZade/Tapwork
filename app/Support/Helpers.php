<?php

use Carbon\Carbon;

if (!function_exists('parseAzeriDate')) {
    function parseAzeriDate(string $azeriDate): ?string
    {
        $monthMap = [
            'Yanvar' => 'January',
            'Fevral' => 'February',
            'Mart' => 'March',
            'Aprel' => 'April',
            'May' => 'May',
            'İyun' => 'June',
            'İyul' => 'July',
            'Avqust' => 'August',
            'Sentyabr' => 'September',
            'Oktyabr' => 'October',
            'Noyabr' => 'November',
            'Dekabr' => 'December',
        ];

        foreach ($monthMap as $az => $en) {
            if (str_contains($azeriDate, $az)) {
                $converted = str_replace($az, $en, $azeriDate);
                break;
            }
        }

        if (!preg_match('/\d{4}/', $converted ?? '')) {
            $converted .= ' ' . date('Y');
        }

        try {
            return Carbon::parse($converted)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('normalizeRelativeDate')) {
    function normalizeRelativeDate(string $value): string
    {
        if ($value === 'Bu gün') {
            return date('d F Y');
        } elseif ($value === 'Dünən') {
            return date('d F Y', strtotime('-1 day'));
        }

        return $value;
    }
}
