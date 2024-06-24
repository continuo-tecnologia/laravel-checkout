<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use MatheusFS\Laravel\Checkout\Models\Transaction as TransactionModel;

class Transaction{

    protected $transaction;
    protected $status;
    protected $payment_method;
    protected $customer;
    protected $billing;
    protected $shipping;
    protected $items;

    protected $fake;

    /**
     * @param Item[] $items
     */
    public function __construct(
        string $payment_method,
        Customer $customer,
        Billing $billing,
        Shipping $shipping,
        $items
    ){

        $this->payment_method = $payment_method;
        $this->customer = $customer;
        $this->billing = $billing;
        $this->shipping = $shipping;
        $this->items = $items;

        $this->buildTransaction();
    }

    function get_amount(){

        $to_prices_sum = fn($total, $item) => $total += $item->unit_price;
        return array_reduce($this->items, $to_prices_sum);
    }

    function set_status($status){

        $this->status = $status;
    }

    public function createTransaction($status){

        $this->set_status($status);
        $this->buildTransaction();

        if($this->fake){

            $transaction = new TransactionModel;
            $transaction->data = json_encode($this->transaction);

            $transaction->save();

            return $transaction;
        }
        else{

            return Api::client()->transactions()->create($this->transaction);
        }
    }

    public function buildTransaction(float $amount = null){

        $amount = $amount ?? $this->get_amount();

        $this->transaction = [
            'amount' => $amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'customer' => $this->customer,
            'billing' => $this->billing,
            'shipping' => $this->shipping,
            'items' => $this->items,
        ];
    }

    public function getTransactionEvents(){

        //
    }

    function fake(){

        $this->fake = true;
    }

    static function get_example($name, $as_array = true){

        $parent = dirname(dirname(dirname(dirname(__DIR__))));
        $directory = $parent . '/storage/examples/transaction';
        $example = file_get_contents("$directory/$name.json");

        return json_decode($example, $as_array);
    }
}