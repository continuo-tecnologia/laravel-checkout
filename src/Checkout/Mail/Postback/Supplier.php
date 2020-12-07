<?php

namespace MatheusFS\Laravel\Checkout\Mail\Postback;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Supplier extends Mailable {

    use Queueable, SerializesModels;

    public $supplier_name;
    public $supplier_logo;
    public $customer;
    public $shipping;
    public $items;
    public $status;
    public $payment_method;

    public function __construct(
        $supplier_name,
        $supplier_logo,
        $customer,
        $shipping,
        $items,
        $status,
        $payment_method
    ) {
        $this->supplier_name = $supplier_name;
        $this->supplier_logo = $supplier_logo;
        $this->customer = $customer;
        $this->shipping = $shipping;
        $this->items = $items;
        $this->status = $status;
        $this->payment_method = $payment_method;
    }

    public function build() {

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set(config('checkout.date_timezone'));
        
        return $this->subject('Venda realizada com ' . config('checkout.name') . '!')
        ->from(config('checkout.mailling.from'), config('checkout.name'))
        ->markdown('checkout::mail.postback.supplier');
    }
}
