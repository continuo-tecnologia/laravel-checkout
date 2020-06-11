@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data->current_status))}}
{!!$status::instruction($data->current_status)!!}

<br>

@component('checkout::mail.postback.components.boleto')

@component('checkout::mail.postback.components.shipping')
Seu produto será entregue por {{$transaction->shipping['name']}} em
@endcomponent

<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent