<?php

namespace MatheusFS\LaravelCheckoutPagarMe\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MatheusFS\LaravelCheckoutPagarMe\Transaction;

class Postback extends Mailable {

    use Queueable, SerializesModels;

    const FROM = 'contato@refreshertrends.com.br';
    public $data;
    public $transaction = Transaction::class;

    public function __construct($data) {
        
        $data['transaction'] = (object) $data['transaction'];
        $this->data = (object) $data;
    }

    public function build() {
        
        return $this->subject($this->transaction::getPropertyFrom('subject', $this->data->current_status))
        ->from(Postback::FROM)
        ->markdown('checkout::mail.postback');
    }
}
