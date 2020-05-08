<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

class Transaction {

    const STATUS = [
        'processing' => [
            'as' => 'Pagamento processando',
            'subject' => 'Quase lá! Seu pagamento está sendo processado',
            'description' => 'Transação está em processo de autorização.',
            'instruction' => 'Estamos quase lá! Seu pagamento está sendo analisado pelo nosso sistema e logo que o processo for finalizado você receberá um e-mail de confirmação.',
        ],
        'authorized' => [
            'as' => 'Seu pagamento foi autorizado :)',
            'subject' => 'Parabéns! Seu pagamento foi autorizado',
            'description' => 'Transação foi autorizada. Cliente possui saldo na conta e este valor foi
            reservado para futura captura, que deve acontecer em até 5 dias para transações criadas com api_key.
            Caso não seja capturada, a autorização é cancelada automaticamente pelo banco emissor, e o status
            dela permanece como authorized.',
            'instruction' => 'Recebemos seu pagamento e sua compra está aprovada!<br><br>.
            Em breve você receberá um novo e-mail com as instruções sobre a entrega do seu produto.',
        ],
        'paid' => [
            'as' => 'Pagamento identificado',
            'subject' => 'Parabéns! Seu pagamento foi aprovado :)',
            'description' => 'Transação paga. Foi autorizada e capturada com sucesso.
            Para Boleto, significa que nossa API já identificou o pagamento de seu cliente.',
            'instruction' => 'Ótimo! Seu pagamento foi identificado e sua compra está aprovada.<br><br>
            Em breve você receberá um novo e-mail com as instruções sobre a entrega do seu produto.',
        ],
        'refunded' => [
            'as' => 'Pagamento reembolsado',
            'subject' => 'Sem problemas! Pagamento reembolsado',
            'description' => 'Transação estornada completamente.',
            'instruction' => 'Tudo bem, seu pagamento foi estornado integralmente.<br>
            Continuamos à sua disposição por aqui, caso você precise.',
        ],
        'waiting_payment' => [
            'as' => 'Aguardando pagamento',
            'subject' => 'Falta pouco! O boleto chegou',
            'description' => 'Transação aguardando pagamento (status válido para Boleto bancário).',
            'instruction' => 'Aqui está o seu boleto. Após o seu pagamento, a compensação bancária pode demorar até 48h úteis para que haja confirmação da compra.<br><br>
            Abaixo pode escolher:<br><ul><li>Copiar a linha digitável e colar em seu aplicativo de pagamento ou internet banking preferido</li>
            <li>Abrir o boleto completo para impressão ou leitura do código de barras</li></ul>',
        ],
        'pending_refund' => [
            'as' => 'Processando reembolso',
            'subject' => 'Quase lá! O reembolso está sendo processado',
            'description' => 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado.',
            'instruction' => '',
        ],
        'refused' => [
            'as' => 'Pagamento recusado',
            'subject' => 'Desculpe! Devemos tentar de novo?',
            'description' => 'Transação recusada, não autorizada.',
            'instruction' => '',
        ],
        'chargedback' => [
            'as' => 'Chargeback',
            'subject' => '',
            'description' => 'Transação sofreu chargeback. Veja mais sobre isso em nossa central de ajuda',
            'instruction' => '',
        ],
        'analyzing' => [
            'as' => 'Analisando pagamento',
            'subject' => 'Falta pouco! Estamos checando os últimos detalhes',
            'description' => 'Transação encaminhada para a análise manual
            feita por um especialista em prevenção a fraude.',
            'instruction' => '',
        ],
        'pending_review' => [
            'as' => 'Revisão pendente',
            'subject' => 'Falta pouco! Estamos checando os últimos detalhes',
            'description' => 'Transação pendente de revisão manual por parte do lojista.
            Uma transação ficará com esse status por até 48 horas corridas.',
            'instruction' => '',
        ],
    ];

    public static function as ($current_status) {return Transaction::getPropertyFrom('as', $current_status);}
    public static function subject($current_status) {return Transaction::getPropertyFrom('subject', $current_status);}
    public static function description($current_status) {return Transaction::getPropertyFrom('description', $current_status);}
    public static function instruction($current_status) {return Transaction::getPropertyFrom('instruction', $current_status);}

    public static function getPropertyFrom(string $property, string $current_status) {

        return Transaction::STATUS[$current_status][$property];
    }
}