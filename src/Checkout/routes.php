<?php

Route::namespace('MatheusFS\Laravel\Checkout')->group(function(){

    Route::post('checkout/pagarme/postback/orders', 'Payment\Gateways\PagarMe\Postback@orders')->name('checkout.pagarme.postback.orders');
    Route::post('checkout/pagarme/postback/transactions', 'Payment\Gateways\PagarMe\Postback@transactions')->name('checkout.pagarme.postback.transactions');

    // Route::post('cart/count', 'Controllers\CartController@count')->name('cart.count');
    // Route::post('cart/html', 'Controllers\CartController@html')->name('cart.html');
    // Route::post('cart/add', 'Controllers\CartController@add')->name('cart.add');
    // Route::post('cart/remove', 'Controllers\CartController@remove')->name('cart.remove');
});