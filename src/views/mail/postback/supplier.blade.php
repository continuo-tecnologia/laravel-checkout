<?php
$product = App\Models\Marketplace\Product::find($data['items'][0]['id']);
$supplier = $product->supplier;
?>
@component('mail::message')
<img src="{{$supplier->image_logo}}" alt="{{$supplier->nome_empresa}}" width="200" height="200">
# Parabéns! Você acabou de vender no REFERSHER Marketplace!
<br>

## Itens vendidos
@include('checkout::mail.postback.components.items')
<br>

## Informações do comprador
**Nome:** {{$data['customer']['name']}}
**E-mail:** {{$data['customer']['email']}}
**Documento ({{$data['customer']['documents'][0]['type']}}):** {{$data['customer']['documents'][0]['number']}}
**Telefone:** {{$data['customer']['phone_numbers'][0]}}
<br>

## Informações de entrega
@component('checkout::mail.postback.components.shipping')
Seu(s) produto(s) será entregue por {{$data['shipping']['name']}} em
@endcomponent

<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent