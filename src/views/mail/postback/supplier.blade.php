<?php
$product = App\Models\Marketplace\Product::find($data['items'][0]['id']);
$supplier = $product->supplier;
?>
@component('mail::message')
# Olá {{$supplier->nome_empresa}}, {{strtolower($status::as($data['status']))}}

<br>

@include('checkout::mail.postback.components.items')

<br>

@component('checkout::mail.postback.components.shipping')
Seu(s) produto(s) será entregue por {{$data['shipping']['name']}} em
@endcomponent

<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent