<?php

namespace MatheusFS\Laravel\Checkout\Shipping\Gateways\Jadlog;

class Freight {

    protected $cepori;
    protected $cepdes;
    protected $peso;
    protected $vldeclarado;
    protected $cnpj;
    protected $conta;
    protected $contrato;
    protected $modalidade;
    protected $tpentrega;
    protected $tpseguro;
    protected $vlcoleta;
    protected $frap;

    /**
     * New Jadlog API Freight instance
     * 
     * @param string $origin_zipcode Origin zipcode
     * @param string $destination_zipcode Destination zipcode
     * @param float $weight Package weight
     * @param float $nfe_amount Declared NF-e amount
     * @param string $taker_document Taker document (CNPJ)
     * @param string $jadlog_account Jadlog corrent account
     * @param string $jadlog_contract_number Jadlog contract number
     * @param integer $transportation_mode Transportation mode
     * @param string $delivery_mode Delivery mode: (D)omicílio / (R)etira
     * @param string $insurance_mode Insurance mode: (N)ormal / (A)pólice
     * @param boolean $collect_amount Collect amount negociated w/ Jadlog
     * @param boolean $collect_from_destinatary Collect freight cost from destinatary
     * 
     * @return MatheusFS\Laravel\Checkout\Shipping\Gateways\Jadlog\Freight
     */
    public function __construct(
        $origin_zipcode, 
        $destination_zipcode, 
        $weight, 
        $nfe_amount,
        $taker_document = '12345678901234',
        $jadlog_account = '000001',
        $jadlog_contract_number = '123',
        $transportation_mode = 3,
        $delivery_mode = 'D',
        $insurance_mode = 'N',
        $collect_amount = null,
        $collect_from_destinatary = false
    ) {
        $this->cepori = $origin_zipcode;
        $this->cepdes = $destination_zipcode;
        $this->peso = $weight;
        $this->vldeclarado = $nfe_amount;
        $this->cnpj = $taker_document;
        $this->conta = $jadlog_account;
        $this->contrato = $jadlog_contract_number;
        $this->modalidade = $transportation_mode;
        $this->tpentrega = $delivery_mode;
        $this->tpseguro = $insurance_mode;
        $this->vlcoleta = $collect_amount;
        $this->frap = $collect_from_destinatary;
    }

    /**
     * Simulate freight
     * 
     * @return array Response freight
     */
    public function simulate(){

        return Api::freight($this->payload());
    }

    /**
     * Format payload data for API request
     * 
     * @return array HTTP Request Payload
     */
    public function payload(){
        
        return ["frete" => [get_object_vars($this)]];
    }
}