<?php

namespace MatheusFS\LaravelCheckout\Shipping\Gateways\Jadlog;

class TransportationMode {

    /** @var integer EXPRESSO code (Aéreo) */
    const EXPRESSO = 0;

    /** @var integer PACKAGE code (Rodoviário) */
    const PACKAGE = 3;

    /** @var integer RODOVIARIO code (Rodoviário) */
    const RODOVIARIO = 4;

    /** @var integer ECONOMICO code (Rodoviário) */
    const ECONOMICO = 5;

    /** @var integer DOC code (Rodoviário) */
    const DOC = 6;

    /** @var integer CORPORATE code (Aéreo) */
    const CORPORATE = 7;

    /** @var integer COM code (Aéreo) */
    const COM = 9;

    /** @var integer INTERNACIONAL code (Aéreo) */
    const INTERNACIONAL = 10;

    /** @var integer CARGO code (Aéreo) */
    const CARGO = 12;

    /** @var integer EMERGENCIAL code (Rodoviário) */
    const EMERGENCIAL = 14;

    /** @var integer PICKUP code (Aéreo) [Obrigatório informar o CdPickupDes] */
    const PICKUP = 40;
}