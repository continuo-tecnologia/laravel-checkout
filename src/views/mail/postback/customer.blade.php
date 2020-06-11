@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data['status']))}}
{!!$status::instruction($data['status'])!!}

<br>

@include('checkout::mail.postback.components.boleto')

<br>

@include('checkout::mail.postback.components.items')

@component('checkout::mail.postback.components.shipping')
Seu pedido será entregue por {{$data['shipping']['name']}} em
@endcomponent

<br><hr><br>
<p>Continuamos à sua disposição por aqui, caso necessite.
<br><br>
Cordialmente,<br>
Equipe de Atendimento<br>
{{ strtoupper(config('app.name')) }} Marketplace</p>
@endcomponent