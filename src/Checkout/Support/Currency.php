<?php

namespace MatheusFS\Laravel\Checkout\Support;

class Currency{

    /**
     * Format amount to BRL currency
     * 
     * @param float $amount The numeric BRL amount.
     * @return string Formated BRL currency.
     */
    public static function BRL($amount){

        return self::format('BRL', $amount);
    }

    /**
     * Format amount to currency
     * 
     * @param string $code The 3-letter ISO 4217 currency code indicating the currency to use.
     * @param float $amount The numeric currency amount.
     * @return string Formated currency
     */
    public static function format($code, $amount){

        $fmt = numfmt_create('pt_BR', \NumberFormatter::CURRENCY);
        return numfmt_format_currency($fmt, $amount, $code);
    }
}