<b>Nome:</b> {{ $customer['name'] }}<br>
<b>E-mail:</b> {{ $customer['email'] }}<br>
<b>Documento ({{ $customer['documents'][0]['type'] }}):</b> 
    {{ $customer['documents'][0]['number'] }}<br>
<b>Telefone:</b> {{ $customer['phone_numbers'][0] }}