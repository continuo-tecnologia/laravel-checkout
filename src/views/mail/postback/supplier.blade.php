@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data['status']))}}
{!!$status::instruction($data['status'])!!}

<br>

@include('checkout::mail.postback.components.items')

<br>

@component('checkout::mail.postback.components.shipping')
Seu produto será entregue por {{$data['shipping']['name']}} em
@endcomponent

<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent