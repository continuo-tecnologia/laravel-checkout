<?php

namespace MatheusFS\Laravel\Checkout\Contracts;

interface Order{

    public static function status(array $normalized);
    public static function customer(array $normalized);
}