<?php

namespace MatheusFS\Laravel\Checkout\Facades;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use MatheusFS\Laravel\Checkout\Entities\Item;

class Cart {
    
    public static function total($cart_key){
        
        return self::subtotal($cart_key) + self::freight($cart_key);
    }

    public static function freight($cart_key){
        
        return 0;
    }

    public static function subtotal($cart_key){
        
        return self::collect($cart_key)->reduce(function($total, $item){return $total += $item->unit_price * $item->quantity;});
    }

    public static function countItems($cart_key){
        
        return self::collect($cart_key)->reduce(function($total, $item){
            
            return $total + $item->quantity;
        }, 0);
    }

    public static function renderMinicart($cart_key){

        $items = self::collect($cart_key);
        $subtotal = self::subtotal();
        $freight = self::freight();
        $total = self::total();
        return view('marketplace.minicart', compact('items', 'subtotal', 'freight', 'total'));
    }

    public static function hasItem($cart_key, $item_id){

        $cart = self::collect($cart_key);
        return $cart->contains('id', $item_id);
    }

    public static function collect($cart_key){
        
        return collect(self::items($cart_key));
    }

    public static function items($cart_key){

        return Cache::get($cart_key) ?? [];
    }
}