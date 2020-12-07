<?php

namespace MatheusFS\Laravel\Checkout\Mail\Postback;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;

class Customer extends Mailable {

    use Queueable, SerializesModels;

    public $customer;
    public $shipping;
    public $items;
    public $status;
    public $payment_method;
    public $boleto;

    public function __construct(
        $customer,
        $shipping,
        $items,
        $status,
        $payment_method,
        $boleto
    ) {
        $this->customer = $customer;
        $this->shipping = $shipping;
        $this->items = $items;
        $this->status = $status;
        $this->payment_method = $payment_method;
        $this->boleto = $boleto;
    }

    public function build() {

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set(config('checkout.date_timezone'));

        return $this->subject($this->status['subject'])
        ->from(config('checkout.mailling.from'), config('checkout.name'))
        ->markdown('checkout::mail.postback.customer');
    }
}
