@component('mail::message')

<h2>Olá {{ $customer['name'] }}, {{ strtolower($status['alias']) }}</h2>
{!! $status['instruction'] !!}

@if($status['id']=='waiting_payment')
<br>
@include('checkout::mail.postback.components.boleto')
@endif

<br><hr><br>

<h2>Itens comprados</h2>
@include('checkout::mail.postback.components.items')
<br><hr><br>

@isset($shipping)
@include('checkout::mail.postback.components.shipping')
<br><hr><br>
@endisset

<h2>Suas informações</h2>
@include('checkout::mail.postback.components.customer')
<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent
