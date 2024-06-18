<?php

namespace MatheusFS\Laravel\Checkout\Support\Facades;

use Illuminate\Foundation\Auth\User;
use MatheusFS\Laravel\Checkout\Checkout;

class Order{

    static function last($item_key = null){

        return self::history(request()->user(), $item_key)->first();
    }

    static function history(User $user = null, $item_key = null){

        $checkout = new Checkout;

        if(!is_null($user)){

            $transactions = collect($checkout->user_orders());
        }
        else $transactions = collect($checkout->orders());

        if(!is_null($item_key)){

            $transactions = self::filter_transactions_from_item($transactions, $item_key);
        }

        return $transactions;
    }

    static function last_from_all(){

        //
    }

    static function filter_transactions_from_user($transactions, $user){

        return $transactions->filter(function($transaction) use ($user){

            $customer_external_id = $transaction->customer->external_id;
            $customer_email = $transaction->customer->email;

            $matched_by_id = $user->key === $customer_external_id;
            $matched_by_email = $user->email === $customer_email;

            return $matched_by_id || $matched_by_email;
        });
    }

    static function filter_transactions_from_item($transactions, $item_key){

        $items_contains_item_key = fn($transaction) => collect($transaction->items)->contains('id', $item_key);

        return $transactions->filter($items_contains_item_key);
    }
}