<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use MatheusFS\Laravel\Checkout\Facades\Mailer;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Api;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Postback;

Route::prefix('checkout')->name('checkout.')->namespace('MatheusFS\Laravel\Checkout')->group(function(){

    Route::post('pagarme/postback/orders', 'Payment\Gateways\PagarMe\Postback@orders')->name('pagarme.postback.orders');
    Route::post('pagarme/postback/transactions', 'Payment\Gateways\PagarMe\Postback@transactions')->name('pagarme.postback.transactions');
    
    Route::post('pagarme/capture', function(Request $request){
        
        $captured_transaction = Api::client()->transactions()->capture([
            'id' => $request->id,
            'amount' => $request->amount
        ]);
        
        return response()->json($captured_transaction);
    })->name('pagarme.capture');
    
    Route::post('mail/postback/customer/render', function(Request $request){
        
        $normalized = Postback::normalizeTransactionData($request);
        return (Mailer::getCustomerMailable($normalized))->render();
    })->name('mail.postback.customer.render');

    Route::post('shipping/correios/freight/{from}/{to}', 'Shipping\Carriers\Correios@getFreight')->name('shipping.correios.freight');
    Route::post('shipping/correios/zipcode/{zipcode}', 'Shipping\Carriers\Correios@getZipcode')->name('shipping.correios.zipcode');
    
    // Route::post('cart/count', 'Controllers\CartController@count')->name('cart.count');
    // Route::post('cart/html', 'Controllers\CartController@html')->name('cart.html');
    // Route::post('cart/add', 'Controllers\CartController@add')->name('cart.add');
    // Route::post('cart/remove', 'Controllers\CartController@remove')->name('cart.remove');
});
