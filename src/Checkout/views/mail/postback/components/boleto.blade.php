@if($payment_method == 'boleto' && isset($boleto))

@component('mail::panel')
<small><i>Linha digitável</i></small><br>
{{ $boleto['barcode'] }}
@endcomponent

@component('mail::button', ['url' => $boleto['url']])
Boleto completo
@endcomponent

<p style="text-align: center">
    Vencimento: 
    <b>{{ strftime('%d de %B (%A)', strtotime($boleto['expiration_date'])) }}</b>
</p>

@endif