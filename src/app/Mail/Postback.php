<?php

namespace MatheusFS\LaravelCheckoutPagarMe\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Postback extends Mailable {

    use Queueable, SerializesModels;

    const FROM = 'matheus@refresher.com.br';
    public $data;

    public function __construct($data) {
        
        $this->data = $data;
    }

    public function build() {

        return $this->from(Postback::FROM)->view('checkout::mail.postback');
    }
}
