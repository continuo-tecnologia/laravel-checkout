<h2>Seu pedido será entregue em</h2>
@component('mail::panel')
<p style="text-align: center">
{{ $shipping['address']['street'] }},
{{ $shipping['address']['street_number'] }} - 
{{ $shipping['address']['neighborhood'] }}<br>
{{ $shipping['address']['city'] }} -
{{ $shipping['address']['state'] }}<br>
<i><small>({{ $shipping['address']['zipcode'] }})</small></i><br>
</p>
@endcomponent

<p style="text-align: center">
    Data estimada (após confirmação do pagamento): 
    <b>{{ $shipping['days_to_deliver'] ?? 21 }} dia(s) úteis</b>
</p>
