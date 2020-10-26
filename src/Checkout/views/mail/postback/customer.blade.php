@component('mail::message')

<h2>Olá {{ $name }}, {{ strtolower($status::as($data['status'])) }}</h2>
{!!$status::instruction($data['status'])!!}
<br>
@include('checkout::mail.postback.components.boleto')
<br><hr><br>

<h2>Itens comprados</h2>
@include('checkout::mail.postback.components.items')
<br><hr><br>

@isset($data['shipping'])
    <h2>Seu pedido será entregue por {{ $data['shipping']['name'] }} em</h2>
    @include('checkout::mail.postback.components.shipping')
    <br><hr><br>
@endisset

<h2>Suas informações</h2>
@include('checkout::mail.postback.components.customer')
<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent
