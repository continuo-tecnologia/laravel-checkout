<?php

Route::post('api/pagarme/postback/orders', 'PagarMe\Postback@orders')->name('api.pagarme.postback.orders');
Route::post('api/pagarme/postback/transactions', 'PagarMe\Postback@transactions')->name('api.pagarme.postback.transactions');