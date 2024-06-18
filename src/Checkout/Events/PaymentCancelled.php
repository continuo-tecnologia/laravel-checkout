<?php

namespace MatheusFS\Laravel\Checkout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCancelled{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(array $order){

        $this->order = $order;
    }
}