<?php

namespace MatheusFS\LaravelCheckout\Entities;

class Item {

    public $id;
    public $title;
    public $unit_price;
    public $quantity = 1;

    public function __construct($id, $title, $unit_price, $quantity = 1) {
        
        $this->id = $id;
        $this->title = $title;
        $this->unit_price = $unit_price;
        $this->quantity = $quantity;
    }

    public function price(){
        
        return $this->unit_price * $this->quantity;
    }

    public function __toString(){
        
        $item = $this;
        return (string) view('checkout::entities.item', compact('item'))->render();
    }
}