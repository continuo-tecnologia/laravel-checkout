@if($payment_method == 'boleto' && isset($boleto))

<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td class="panel-content">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="panel-item">
                        <small><i>Linha digitável</i></small><br>
                        {{ $boleto['barcode'] }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@component('mail::button', ['url' => $boleto['url']])
Boleto completo
@endcomponent

<p style="text-align: center">
    Vencimento: 
    <b>{{ strftime('%d de %B (%A)', strtotime($boleto['expiration_date'])) }}</b>
</p>

@endif