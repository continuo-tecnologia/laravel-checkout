@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data->current_status))}}
{!!$status::instruction($data->current_status)!!}

<br>

@include('checkout::mail.postback.components.boleto')

@component('checkout::mail.postback.components.shipping')
Seu pedido será entregue por {{$transaction->shipping['name']}} em
@endcomponent

<br><hr><br>
<p>Continuamos à sua disposição por aqui, caso necessite.
<br><br>
Cordialmente,<br>
Equipe de Atendimento<br>
{{ strtoupper(config('app.name')) }} Marketplace</p>
@endcomponent