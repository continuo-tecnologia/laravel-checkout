<?php

Route::post('checkout/pagarme/postback/orders', 'MatheusFS\LaravelCheckout\Payment\Gateways\PagarMe\Postback@orders')->name('checkout.pagarme.postback.orders');
Route::post('checkout/pagarme/postback/transactions', 'MatheusFS\LaravelCheckout\Payment\Gateways\PagarMe\Postback@transactions')->name('checkout.pagarme.postback.transactions');