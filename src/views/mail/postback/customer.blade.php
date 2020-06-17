@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data['status']))}}
{!!$status::instruction($data['status'])!!}

<br>

@include('checkout::mail.postback.components.boleto')

<br>
## Itens comprados
@include('checkout::mail.postback.components.items')

## Informações de entrega
@component('checkout::mail.postback.components.shipping')
Seu pedido será entregue por {{$data['shipping']['name']}} em
@endcomponent

<br><hr><br>
@include('checkout::mail.postback.components.signature')

@endcomponent