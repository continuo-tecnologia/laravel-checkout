<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

class Transaction {

    public function createTransaction() {

        return $this->client->transactions()->create($this->transaction);
    }

    public function buildTransaction(float $amount) {

        $this->transaction = [
            'amount' => $amount,
            'payment_method' => $this->payment_method,
            'customer' => $this->customer,
            'billing' => $this->billing,
            'shipping' => $this->shipping,
            'items' => $this->items,
        ];
    }

    public function getTransactionEvents() {
        //
    }
}