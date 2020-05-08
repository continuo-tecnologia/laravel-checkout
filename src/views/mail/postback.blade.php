@component('mail::message')

# Olá {{$name}}, {{strtolower($Transaction::as($data->current_status))}}
{!!$Transaction::instruction($data->current_status)!!}

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

@if(!empty($transaction->shipping))
<br><hr><br>
<h1>Seu pedido será entregue por {{$transaction->shipping['name']}} em</h1>
@component('mail::panel')
<p style="text-align: center">{{$transaction->shipping['address']['street']}},
{{$transaction->shipping['address']['street_number']}} - 
{{$transaction->shipping['address']['neighborhood']}}<br>
{{$transaction->shipping['address']['city']}} -
{{$transaction->shipping['address']['state']}}<br>
<i><small>({{$transaction->shipping['address']['zipcode']}})</small></i><br></p>
@endcomponent

<p style="text-align: center">
    Data estimada (após confirmação do pagamento): 
    <b>{{$delivery_days}} dia(s) úteis</b>
</p>

@endif
<br><hr><br>
<p>Continuamos à sua disposição por aqui, caso necessite.
<br><br>
Cordialmente,<br>
Equipe de Atendimento<br>
{{ strtoupper(config('app.name')) }} Marketplace</p>
@endcomponent