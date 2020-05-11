<?php

Route::post('checkout/pagarme/postback/orders', 'MatheusFS\LaravelCheckout\PagarMe\Postback@orders')->name('checkout.pagarme.postback.orders');
Route::post('checkout/pagarme/postback/transactions', 'MatheusFS\LaravelCheckout\PagarMe\Postback@transactions')->name('checkout.pagarme.postback.transactions');