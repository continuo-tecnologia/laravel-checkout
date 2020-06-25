<?php

namespace MatheusFS\Laravel\Checkout\Mail\Postback;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;

class Development extends Mailable {

    use Queueable, SerializesModels;

    const FROM = 'contato@refreshertrends.com.br';
    public $data;
    public $status = Status::class;
    public $name;
    public $delivery_days;

    public function __construct($data) {
        
        $name = $data['customer']['name'];
        
        $this->data = $data;
        $this->name = explode(' ', $name)[0];

        $now = new DateTime();
        $delivery_date = new DateTime($data['shipping']['delivery_date']);
        $this->delivery_days = $now->diff($delivery_date)->d;
    }

    public function build() {

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        return $this->subject(Status::subject($this->data['status']))
        ->from(Development::FROM, 'REFRESHER Marketplace')
        ->markdown('checkout::mail.postback.development');
    }
}
