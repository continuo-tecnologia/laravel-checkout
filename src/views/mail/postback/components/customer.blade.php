<b>Nome:</b> {{$data['customer']['name']}}<br>
<b>E-mail:</b> {{$data['customer']['email']}}<br>
<b>Documento ({{$data['customer']['documents'][0]['type']}}):</b> 
    {{$data['customer']['documents'][0]['number']}}<br>
<b>Telefone:</b> {{$data['customer']['phone_numbers'][0]}}