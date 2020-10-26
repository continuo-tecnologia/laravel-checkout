@component('mail::panel')
<p style="text-align: center">{{$data['shipping']['address']['street']}},
{{$data['shipping']['address']['street_number']}} - 
{{$data['shipping']['address']['neighborhood']}}<br>
{{$data['shipping']['address']['city']}} -
{{$data['shipping']['address']['state']}}<br>
<i><small>({{$data['shipping']['address']['zipcode']}})</small></i><br></p>
@endcomponent

<p style="text-align: center">
    Data estimada (após confirmação do pagamento): 
    <b>{{$delivery_days ?? 14}} dia(s) úteis</b>
</p>
