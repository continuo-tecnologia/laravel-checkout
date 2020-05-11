@component('mail::message')

# Olá {{$name}}, {{strtolower($status::as($data->current_status))}}
{!!$status::instruction($data->current_status)!!}

<br>

@if($transaction->payment_method=='boleto')

@component('mail::panel')
<small><i>Linha digitável</i></small><br>
{{$transaction->boleto_barcode}}
@endcomponent

@component('mail::button', ['url' => $transaction->boleto_url])
Boleto completo
@endcomponent

<p style="text-align: center">
    Vencimento: 
    <b>{{strftime('%d de %B (%A)', strtotime($transaction->boleto_expiration_date))}}</b>
</p>

@endif

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