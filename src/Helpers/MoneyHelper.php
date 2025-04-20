<?php

if (! function_exists('money_format')) {
    function money_format($number, $showZeros = true, $decimals = 2, $showCurrency = true)
    {
        if (app()->getLocale() == 'es') {
            $decimalSeparator = ',';
            $thousandSeparator = '.';
        } else {
            $decimalSeparator = '.';
            $thousandSeparator = ',';
        }

        $formatted = number_format($number, $decimals, $decimalSeparator, $thousandSeparator);

        if (!$showZeros) {
            $replace = $decimalSeparator . str_repeat('0', $decimals);
            $formatted = str_replace($replace, '', $formatted);
        }

        if ($showCurrency) {
            $currency = config('site.currency.symbol');
            $formatted = $currency . $formatted;
        }

        return $formatted;
    }
}