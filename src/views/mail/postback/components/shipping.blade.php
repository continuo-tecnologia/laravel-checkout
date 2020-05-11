@if(!empty($transaction->shipping))

<br><hr><br>
<h1>{{$slot}}</h1>
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