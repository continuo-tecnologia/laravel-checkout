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