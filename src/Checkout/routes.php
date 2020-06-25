<?php

Route::post('checkout/pagarme/postback/orders', 'MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Postback@orders')->name('checkout.pagarme.postback.orders');
Route::post('checkout/pagarme/postback/transactions', 'MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Postback@transactions')->name('checkout.pagarme.postback.transactions');