<?php

namespace MatheusFS\Laravel\Checkout\Support\Facades;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Contracts\Order as OrderContract;

class Order implements OrderContract{

    static function status(array $normalized){

        return $normalized['status'];
    }

    static function customer(array $normalized){

        $external_id = $normalized['customer']['external_id'];
        $user_model = config('checkout.user.model');

        if(class_exists($user_model) && Schema::hasTable((new $user_model)->getTable())){

            $user = $user_model::find($external_id) ?? $user_model::whereEmail($external_id)->first();

            if($user) return $user;
            else dd(compact('external_id', 'user_model', 'user'));
        }
    }

    static function confirm($transaction){

        $checkout = new Checkout;

        $id = $transaction->id;
        $amount = $transaction->amount;

        $user = config('checkout.user.model')::find($transaction->customer->external_id);

        Log::info('Checkout: Identified uncaptured transaction. Capturing...', compact('transaction'));

        try{

            $response = $checkout->client->transactions()->capture(compact('id', 'amount'));

            Log::info('Checkout: Captured transaction', compact('response'));
        }
        catch(\Exception $exception){

            $checkout->invalidate_user_orders(request()->user(), 'authorized');
            $checkout->invalidate_user_orders(request()->user());

            Log::debug('Checkout: Error capturing transaction. Invalidated authorized user orders cache', compact('exception'));
        }
    }

    static function last($item_key = null){

        $user = request()->user();

        return self::history($user, $item_key)->firstWhere('status', '<>', 'refused');
    }

    static function history(User $user = null, $item_key = null){

        $checkout = new Checkout;

        if(is_null($user)) $transactions = collect($checkout->orders());
        else $transactions = collect($checkout->user_orders());

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

    static function filter_transactions_from_item(Collection $transactions, $item_key){

        $items_contains_item_key = fn($transaction) => collect($transaction->items)->contains('id', $item_key);

        return $transactions->filter($items_contains_item_key);
    }
}