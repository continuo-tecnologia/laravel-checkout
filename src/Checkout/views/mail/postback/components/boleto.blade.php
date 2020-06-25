@if($data['payment_method']=='boleto')

@component('mail::panel')
<small><i>Linha digitável</i></small><br>
{{$data['boleto']['barcode']}}
@endcomponent

@component('mail::button', ['url' => $data['boleto']['url']])
Boleto completo
@endcomponent

<p style="text-align: center">
    Vencimento: 
    <b>{{strftime('%d de %B (%A)', strtotime($data['boleto']['expiration_date']))}}</b>
</p>

@endif