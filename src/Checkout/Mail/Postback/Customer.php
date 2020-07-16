<?php

namespace MatheusFS\Laravel\Checkout\Mail\Postback;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;

class Customer extends Mailable {

    use Queueable, SerializesModels;

    const FROM = 'contato@refreshertrends.com.br';
    public $data;
    public $status = Status::class;
    public $name;
    public $delivery_days;

    public function __construct($data) {
        
        $this->data = (array) $data;
        $this->name = explode(' ', $data['customer']['name'])[0];

        $delivery_date = new DateTime($this->data['shipping']['delivery_date']);
        $this->delivery_days = (new DateTime())->diff($delivery_date)->d;
    }

    public function build() {

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return $this->subject(Status::subject($this->data['status']))
        ->from(Customer::FROM, 'REFRESHER Marketplace')
        ->markdown('checkout::mail.postback.customer');
    }
}
