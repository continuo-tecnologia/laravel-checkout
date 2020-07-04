<?php

namespace MatheusFS\Laravel\Checkout\Facades;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
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

    public static function renderMinicart(){

        $items = self::collect();
        $subtotal = self::subtotal();
        $freight = self::freight();
        $total = self::total();
        return view('marketplace.minicart', compact('items', 'subtotal', 'freight', 'total'));
    }

    public static function increment(Item $item){

        $cart = self::collect();

        self::hasItem($item->id)
        ? $cart->firstWhere('id', $item->id)->quantity++
        : $cart->push($item);
        
        Cache::put(self::getId(), $cart);

        return $cart;
    }

    public static function decrement(Item $item){

        $cart = self::collect();

        $cart->firstWhere('id', $item->id)->quantity < 2
        ? $cart = $cart->where('id', '!=', $item->id)
        : $cart->firstWhere('id', $item->id)->quantity--;

        Cache::put(self::getId(), $cart);

        return $cart;
    }

    public static function hasItem($item_id){

        $cart = self::collect();
        return $cart->contains('id', $item_id);
    }

    public static function forget(){
        
        return Cache::forget(self::getId());
    }

    public static function collect(){
        
        return collect(self::items());
    }

    public static function items(){

        return Cache::get(self::getId()) ?? [];
    }

    public static function getId(){

        return 'user::'. Auth::check() ? Auth::id() : Session::getId() .'::cart';
    }
}