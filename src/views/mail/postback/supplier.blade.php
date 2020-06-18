@component('mail::message')

<img src="{{$supplier->imagem_logo}}" alt="{{$supplier->nome_empresa}}" width="80">
<b style="font-size: 50px">+</b>
<img src="{{asset('/images/loja/logo1.png')}}" width="80">
<br>
# Parabéns! Você acabou de vender no REFERSHER Marketplace!
<br>
## Itens vendidos
@include('checkout::mail.postback.components.items')
<br>
## Informações do comprador
@include('checkout::mail.postback.components.customer')
<br>
@include('checkout::mail.postback.components.shipping')
<br><hr><br>
@include('checkout::mail.postback.components.signature')

@endcomponent