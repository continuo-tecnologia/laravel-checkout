<?php

namespace MatheusFS\LaravelCheckout\Shipping\Gateways\Jadlog;

use MatheusFS\LaravelCheckout\Address;
use MatheusFS\LaravelCheckout\Traits\Requestable;

class Entity {

    use Requestable;

    protected $nome;
    protected $cnpjCpf;
    protected $ie;
    protected $endereco;
    protected $numero;
    protected $compl;
    protected $bairro;
    protected $cidade;
    protected $uf;
    protected $cep;
    protected $fone;
    protected $cel;
    protected $email;
    protected $contato;

    /**
     * New Jadlog API PF or PJ entity
     * 
     * @param string $nome Name
     * @param string $cnpjCpf Document number (CNPJ or CPF)
     * @param string $ie State Inscription
     * @param string $endereco Address street
     * @param string $numero Address street number
     * @param string $compl Address street complement
     * @param string $bairro Address neighborhood
     * @param string $cidade Address city
     * @param string $uf Address region
     * @param string $cep Address Zipcode
     * @param string $fone Phone number
     * @param string $cel Mobile number
     * @param string $email E-mail
     * @param string $contato Contact label
     */
    public function __construct(
        $nome,
        $cnpjCpf,
        $ie,
        $endereco,
        $numero,
        $compl,
        $bairro,
        $cidade,
        $uf,
        $cep,
        $fone,
        $cel,
        $email,
        $contato
    ) {
        $this->nome = $nome;
        $this->cnpjCpf = $cnpjCpf;
        $this->ie = $ie;
        $this->endereco = $endereco;
        $this->numero = $numero;
        $this->compl = $compl;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->uf = $uf;
        $this->cep = $cep;
        $this->fone = $fone;
        $this->cel = $cel;
        $this->email = $email;
        $this->contato = $contato;
    }

    public function setAddress(Address $address){
        
        $this->numero = $address->street_number;
        $this->compl = $address->street_complementary;
        $this->endereco = $address->street_name;
        $this->bairro = $address->neighborhood;
        $this->cidade = $address->city;
        $this->uf = $address->state;
    }
}