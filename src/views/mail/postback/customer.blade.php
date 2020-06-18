@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data['status']))}}
{!!$status::instruction($data['status'])!!}
<br>
@include('checkout::mail.postback.components.boleto')
<br>
## Itens comprados
@include('checkout::mail.postback.components.items')
<br>
@include('checkout::mail.postback.components.shipping')
<br>
## Suas informações
@include('checkout::mail.postback.components.customer')
<br><hr><br>
@include('checkout::mail.postback.components.signature')

@endcomponent