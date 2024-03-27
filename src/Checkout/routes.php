<?php

use Illuminate\Support\Facades\Route;

Route::prefix('checkout')->name('checkout.')->namespace('MatheusFS\Laravel\Checkout')->group(function(){

    Route::post('pagarme/postback/orders', 'Payment\Gateways\PagarMe\Postback@orders')->name('pagarme.postback.orders');
    Route::post('pagarme/postback/transactions', 'Payment\Gateways\PagarMe\Postback@transactions')->name('pagarme.postback.transactions');
    
    Route::post('pagarme/capture', 'Payment\Gateways\PagarMe\Api@capture')->name('pagarme.capture');
    
    Route::post('mail/postback/customer/render', 'Controllers\PostbackController@render_normalized')->name('mail.postback.customer.render');

    Route::get('shipping/correios/freight', 'Shipping\Carriers\Correios\Api@getFreight')->name('shipping.correios.freight');
    Route::get('shipping/correios/zipcode', 'Shipping\Carriers\Correios\Api@getZipcode')->name('shipping.correios.zipcode');
    
    // Route::post('cart/count', 'Controllers\CartController@count')->name('cart.count');
    // Route::post('cart/html', 'Controllers\CartController@html')->name('cart.html');
    // Route::post('cart/add', 'Controllers\CartController@add')->name('cart.add');
    // Route::post('cart/remove', 'Controllers\CartController@remove')->name('cart.remove');
});
