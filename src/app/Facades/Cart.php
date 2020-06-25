<?php

namespace MatheusFS\LaravelCheckout\Facades;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use MatheusFS\LaravelCheckout\Entities\Item;

class Cart {
    
    public static function total(){
        
        return Cart::subtotal() + Cart::freight();
    }

    public static function freight(){
        
        return 0;
    }

    public static function subtotal(){
        
        return Cart::collect()->reduce(function($total, $item){return $total += $item->unit_price * $item->quantity;});
    }

    public static function renderModal(){

        $items = Cart::collect();
        $subtotal = Cart::subtotal();
        $freight = Cart::freight();
        $total = Cart::total();
        return view('marketplace.cart', compact('items', 'subtotal', 'freight', 'total'));
    }

    public static function increment(Item $item){

        $cart = Cart::collect();

        Cart::hasItem($item->id)
        ? $cart->firstWhere('id', $item->id)->quantity++
        : $cart->push($item);

        Cache::put(Cart::getId(), $cart);

        return json_encode($cart);
    }

    public static function decrement(Item $item){

        $cart = Cart::collect();

        $cart->firstWhere('id', $item->id)->quantity < 2
        ? $cart = $cart->where('id', '!=', $item->id)
        : $cart->firstWhere('id', $item->id)->quantity--;

        Cache::put(Cart::getId(), $cart);

        return json_encode($cart);
    }

    public static function hasItem($item_id){

        $cart = Cart::collect();
        return $cart->contains('id', $item_id);
    }

    public static function forget(){
        
        return Cache::forget(Cart::getId());
    }

    public static function collect(){
        
        return collect(Cart::all());
    }

    public static function all(){
        
        return Cache::get(Cart::getId()) ?? [];
    }

    public static function getId(){
        
        $id = Auth::check() ? Auth::id() : uniqid();

        return Cache::remember("user:$id:cart", 3600, function(){
            
            return [];
        });
    }
}