<?php

namespace MatheusFS\LaravelCheckoutPagarMe\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Postback extends Mailable {
    use Queueable, SerializesModels;

    public function __construct() {
        //
    }

    public function build() {

        return $this->from('matheus@refresher.com.br')->view('view.name');
    }
}
