<?php

namespace MatheusFS\Laravel\Checkout\Facades;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use MatheusFS\Laravel\Checkout\Entities\Item;

class Cart {
    
    public static function total(){
        
        return self::subtotal() + self::freight();
    }

    public static function freight(){
        
        return 0;
    }

    public static function subtotal(){
        
        return self::collect()->reduce(function($total, $item){return $total += $item->unit_price * $item->quantity;});
    }

    public static function countItems(){
        
        return self::collect()->reduce(function($total, $item){
            
            return $total + $item->quantity;
        }, 0);
    }

    public static function renderMinicart(){

        $items = self::collect();
        $subtotal = self::subtotal();
        $freight = self::freight();
        $total = self::total();
        return view('marketplace.minicart', compact('items', 'subtotal', 'freight', 'total'));
    }

    public static function hasItem($cart_id, $item_id){

        $cart = self::collect($cart_id);
        return $cart->contains('id', $item_id);
    }

    public static function collect($cart_id){
        
        return collect(self::items($cart_id));
    }

    public static function items($cart_id){

        return Cache::get($cart_id) ?? [];
    }
}