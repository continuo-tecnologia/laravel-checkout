<?php

namespace MatheusFS\LaravelCheckout\Shipping\Gateways\Jadlog;

use MatheusFS\LaravelCheckout\Traits\Requestable;

class FiscalDocument {

    use Requestable;

    protected $cfop;
    protected $danfeCte;
    protected $nrDoc;
    protected $serie;
    protected $tpDocumento;
    protected $valor;

    public function __construct(
        $cfop = '6909', 
        $danfeCte = '00000000000000000000000000000000000000000000', 
        $nrDoc = '00000000', 
        $serie = '0', 
        $tpDocumento = 2,
        $valor = 20.2
    ) {
        $this->cfop = $cfop;
        $this->danfeCte = $danfeCte;
        $this->nrDoc = $nrDoc;
        $this->serie = $serie;
        $this->tpDocumento = $tpDocumento;
        $this->valor = $valor;
    }
}