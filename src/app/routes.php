<?php

Route::post('checkout/pagarme/postback/orders', 'MatheusFS\LaravelCheckoutPagarMe\Postback@orders')->name('checkout.pagarme.postback.orders');
Route::post('checkout/pagarme/postback/transactions', 'MatheusFS\LaravelCheckoutPagarMe\Postback@transactions')->name('checkout.pagarme.postback.transactions');