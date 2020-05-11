<?php

namespace MatheusFS\LaravelCheckout\Mail\Postback;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MatheusFS\LaravelCheckout\PagarMe\Status;

class Development extends Mailable {

    use Queueable, SerializesModels;

    const FROM = 'contato@refreshertrends.com.br';
    public $data;
    public $status = Status::class;
    public $transaction;
    public $name;
    public $delivery_days;

    public function __construct($data) {
        
        $name = $data['transaction']['customer']['name'];
        
        $this->data = (object) $data;
        $this->transaction = (object) $data['transaction'];
        
        $this->name = explode(' ', $name)[0];

        $now = new DateTime();
        $delivery_date = new DateTime($this->transaction->shipping['delivery_date']);
        $this->delivery_days = $now->diff($delivery_date)->d;
    }

    public function build() {

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return $this->subject(Status::subject($this->data->current_status))
        ->from(Development::FROM, 'REFRESHER Marketplace')
        ->markdown('checkout::mail.postback');
    }
}
