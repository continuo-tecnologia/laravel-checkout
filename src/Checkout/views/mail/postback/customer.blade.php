@component('mail::message')

<h2>Olá {{ $customer['name'] }}, {{ strtolower($status['alias']) }}</h2>
{!! $status['instruction'] !!}
<br>
@include('checkout::mail.postback.components.boleto')
<br><hr><br>

<h2>Itens comprados</h2>
@include('checkout::mail.postback.components.items')
<br><hr><br>

@isset($shipping)
    <h2>Seu pedido será entregue por {{ $shipping['name'] }} em</h2>
    @include('checkout::mail.postback.components.shipping')
    <br><hr><br>
@endisset

<h2>Suas informações</h2>
@include('checkout::mail.postback.components.customer')
<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent
