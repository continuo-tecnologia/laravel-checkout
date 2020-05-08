@component('mail::message')
# {{$transaction::getPropertyFrom('as', $data->current_status)}}

{!!$transaction::getPropertyFrom('instruction', $data->current_status)!!}
<br>
@if($data->transaction->payment_method=='boleto')
<small><i>Linha digitável</i></small>
@component('mail::panel')
{{$data->transaction->boleto_barcode}}
@endcomponent

@component('mail::button', ['url' => $data->transaction->boleto_url])
Boleto completo
@endcomponent
@endif

Obrigado,<br>
{{ config('app.name') }}
@endcomponent