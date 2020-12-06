@component('mail::message')

<div style="display: grid; grid-template-columns: 90px 50px 90px; align-items: center; width: 100%; justify-content: space-around">
    <img src="{{ $supplier_logo }}" alt="{{ $supplier_name }}" width="90">
    <b style="font-size: 50px; text-align: center">+</b>
    <img src="{{ config('checkout.logo') }}" width="90">
</div>
<br>
# Parabéns! Você acabou de vender no {{ config('checkout.name') }}!
<br><hr><br>

# Itens vendidos
@include('checkout::mail.postback.components.items')
<br><hr><br>

# Informações do comprador
@include('checkout::mail.postback.components.customer')
<br><hr><br>

# O pedido será entregue por {{ $shipping['name'] }} em
@include('checkout::mail.postback.components.shipping')
<br><hr><br>

@include('checkout::mail.postback.components.signature')

@endcomponent