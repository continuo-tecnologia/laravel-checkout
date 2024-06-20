<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

class Item {

    public $id;
    public $title;
    public $unit_price;
    public $quantity;
    public $tangible;

    /**
     * New Pagar.me API Item adapter
     * 
     * @param \MatheusFS\Laravel\Checkout\Item $item
     */
    public function __construct($item) {
        
        $this->id = "$item->id";
        $this->title = $item->title;
        $this->unit_price = intval($item->unit_price * 100);
        $this->quantity = $item->quantity ?? 1;
        $this->tangible = $item->tangible ?? true;
    }
}