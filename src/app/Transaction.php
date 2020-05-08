<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

class Transaction{

    const STATUS = [
        'processing' => [
            'as' => 'Pagamento processando',
            'subject' => 'Quase lá! Seu pagamento está sendo processado',
            'description' => 'Transação está em processo de autorização.',
            'instruction' => ''
        ],
        'authorized' => [
            'as' => 'Pagamento autorizado',
            'subject' => 'Parabéns! Seu pagamento foi autorizado',
            'description' => 'Transação foi autorizada. Cliente possui saldo na conta e este valor foi 
            reservado para futura captura, que deve acontecer em até 5 dias para transações criadas com api_key. 
            Caso não seja capturada, a autorização é cancelada automaticamente pelo banco emissor, e o status 
            dela permanece como authorized.',
            'instruction' => ''
        ],
        'paid' => [
            'as' => 'Pagamento identificado',
            'subject' => 'Parabéns! Identificamos seu pagamento',
            'description' => 'Transação paga. Foi autorizada e capturada com sucesso. 
            Para Boleto, significa que nossa API já identificou o pagamento de seu cliente.',
            'instruction' => ''
        ],
        'refunded' => [
            'as' => 'Pagamento reembolsado',
            'subject' => 'Sem problemas! Pagamento devolvido',
            'description' => 'Transação estornada completamente.',
            'instruction' => ''
        ],
        'waiting_payment' => [
            'as' => 'Aguardando pagamento',
            'subject' => 'Falta pouco! O boleto chegou',
            'description' => 'Transação aguardando pagamento (status válido para Boleto bancário).',
            'instruction' => 'Estamos apenas aguardando seu pagamento para dar prosseguimento!<br><br>
            Abaixo pode escolher:<br><ul><li>Copiar a linha digitável e colar em seu aplicativo de pagamento ou internet banking preferido</li>
            <li>Abrir o boleto completo para impressão ou scaneamento do código de barras</li></ul>'
        ],
        'pending_refund' => [
            'as' => 'Processando reembolso',
            'subject' => 'Quase lá! O reembolso está sendo processado',
            'description' => 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado.',
            'instruction' => ''
        ],
        'refused' => [
            'as' => 'Pagamento recusado',
            'subject' => 'Desculpe! Devemos tentar de novo?',
            'description' => 'Transação recusada, não autorizada.',
            'instruction' => ''
        ],
        'chargedback' => [
            'as' => 'Chargeback',
            'subject' => '',
            'description' => 'Transação sofreu chargeback. Veja mais sobre isso em nossa central de ajuda',
            'instruction' => ''
        ],
        'analyzing' => [
            'as' => 'Analisando pagamento',
            'subject' => 'Falta pouco! Estamos checando os últimos detalhes',
            'description' => 'Transação encaminhada para a análise manual 
            feita por um especialista em prevenção a fraude.',
            'instruction' => ''
        ],
        'pending_review' => [
            'as' => 'Revisão pendente',
            'subject' => 'Falta pouco! Estamos checando os últimos detalhes',
            'description' => 'Transação pendente de revisão manual por parte do lojista. 
            Uma transação ficará com esse status por até 48 horas corridas.',
            'instruction' => ''
        ]
    ];

    public static function getPropertyFrom(string $property, string $status_code){

        return Transaction::STATUS[$status_code][$property];
    }
}