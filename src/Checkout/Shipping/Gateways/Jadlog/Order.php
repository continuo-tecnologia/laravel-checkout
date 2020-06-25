<?php

namespace MatheusFS\Laravel\Checkout\Shipping\Gateways\Jadlog;

use MatheusFS\Laravel\Checkout\Traits\Requestable;

class Order {

    use Requestable;

    protected $conteudo;
    protected $pedido;
    protected $totPeso;    
    protected $totValor;    
    protected $obs;    
    protected $modalidade;    
    protected $contaCorrente;    
    protected $tpColeta;    
    protected $tipoFrete;    
    protected $cdUnidadeOri;    
    protected $cdUnidadeDes;    
    protected $cdPickupOri;    
    protected $cdPickupDes;    
    protected $nrContrato;    
    protected $servico;    
    protected $shipmentId;    
    protected $vlColeta;    
    protected $rem;    
    protected $des;    
    protected $dfe;
    protected $volume;

    /** 
     * New Jadlog API Order
     * 
     * @param string $conteudo Shipping content description
     * @param array $pedido Client's order numbers
     * @param string $totPeso .
     * @param string $totValor .
     * @param string $obs .
     * @param string $modalidade .
     * @param string $contaCorrente .
     * @param string $tpColeta .
     * @param string $tipoFrete .
     * @param string $cdUnidadeOri .
     * @param string $cdUnidadeDes .
     * @param string $cdPickupOri .
     * @param string $cdPickupDes .
     * @param string $nrContrato .
     * @param string $servico .
     * @param string $shipmentId .
     * @param string $vlColeta .
     * @param \MatheusFS\Laravel\Checkout\Shipping\Gateways\Jadlog\Entity $rem .
     * @param \MatheusFS\Laravel\Checkout\Shipping\Gateways\Jadlog\Entity $des .
     * @param string $dfe .
     * @param array $volume . 
     */
    public function __construct(
        $conteudo,
        $pedido,
        $totPeso,
        $totValor,
        $obs,
        $modalidade,
        $contaCorrente,
        $tpColeta,
        $tipoFrete,
        $cdUnidadeOri,
        $cdUnidadeDes,
        $cdPickupOri,
        $cdPickupDes = 'BR00001',
        $nrContrato,
        $servico,
        $shipmentId,
        $vlColeta,
        $rem,
        $des,
        $dfe,
        $volume
    ) {
        $this->conteudo = $conteudo;
        $this->pedido = $pedido;
        $this->totPeso = $totPeso;    
        $this->totValor = $totValor;    
        $this->obs = $obs;    
        $this->modalidade = $modalidade;    
        $this->contaCorrente = $contaCorrente;    
        $this->tpColeta = $tpColeta;    
        $this->tipoFrete = $tipoFrete;    
        $this->cdUnidadeOri = $cdUnidadeOri;    
        $this->cdUnidadeDes = $cdUnidadeDes;    
        $this->cdPickupOri = $cdPickupOri;    
        $this->cdPickupDes = $cdPickupDes;    
        $this->nrContrato = $nrContrato;    
        $this->servico = $servico;    
        $this->shipmentId = $shipmentId;    
        $this->vlColeta = $vlColeta;    
        $this->rem = $rem;    
        $this->des = $des;    
        $this->dfe = $dfe;
        $this->volume = $volume;
    }
}