<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use MatheusFS\Laravel\Checkout\Mail\Postback\Customer;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Api;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Postback;

Route::namespace('MatheusFS\Laravel\Checkout')->group(function(){

    Route::post('checkout/pagarme/postback/orders', 'Payment\Gateways\PagarMe\Postback@orders')->name('checkout.pagarme.postback.orders');
    Route::post('checkout/pagarme/postback/transactions', 'Payment\Gateways\PagarMe\Postback@transactions')->name('checkout.pagarme.postback.transactions');
    
    Route::post('checkout/pagarme/capture', function(Request $request){
        
        $captured_transaction = Api::client()->transactions()->capture([
            'id' => $request->id,
            'amount' => $request->amount
        ]);
        
        return response()->json($captured_transaction);
    })->name('checkout.pagarme.capture');
    
    Route::post('checkout/mail/postback/customer/render', function(Request $request){
        
        $transaction = Postback::normalizeTransactionData($request);
        return (new Customer($transaction))->render();
    })->name('checkout.mail.postback.customer.render');
    
    // Route::post('cart/count', 'Controllers\CartController@count')->name('cart.count');
    // Route::post('cart/html', 'Controllers\CartController@html')->name('cart.html');
    // Route::post('cart/add', 'Controllers\CartController@add')->name('cart.add');
    // Route::post('cart/remove', 'Controllers\CartController@remove')->name('cart.remove');
});
