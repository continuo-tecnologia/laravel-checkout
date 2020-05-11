<?php

/**
* Checkout facade
*
* ...
*
* @copyright 2020 Natheus Ferreira da Silva
* @license https://raw.githubusercontent.com/MatheusFS/laravel-checkout-pagarme/master/LICENSE MIT License
* @version Release: @package_version@
* @link https://packagist.org/packages/matheusfs/laravel-checkout-pagarme
* @since Class available since Release 0.1
*/

namespace MatheusFS\LaravelCheckout;

class Checkout {

    protected $client;
    protected $payment_method;
    protected $customer;
    protected $billing;
    protected $shipping;
    protected $items = array();
    protected $transaction;
    protected $amount = 0;

    /**
    * Initiate facade
    *
    * @param bool $sandbox Checkout in sandbox mode?
    * @param PagarMe\Customer $customer Customer object
    * @param PagarMe\Billing $billing Billing object
    * @param PagarMe\Shipping $shipping Shipping object
    * @return Checkout
    */
    public function __construct(
        bool $sandbox = false,
        ?PagarMe\Customer $customer = null,
        ?PagarMe\Billing $billing = null,
        ?PagarMe\Shipping $shipping = null
    ) {

        $this->client = PagarMe\Api::client($sandbox);
    }

    public function redirectToPaymentLink() {

        $payment_link = new PagarMe\PaymentLink($this, [
            'max_orders' => 1,
            'expires_in' => 60,
            'review_informations' => false
        ]);
        
        return $payment_link->redirect();
    }

    public function addItem(string $id, string $title, float $unit_price, int $quantity = 1, bool $tangible = true): void {
        
        array_push($this->items, [
            'id' => $id,
            'title' => $title,
            'unit_price' => $unit_price * 100,
            'quantity' => $quantity,
            'tangible' => $tangible,
        ]);

        $this->amount += $unit_price; 
    }

    public function setCustomer(PagarMe\Customer $customer, bool $save = true) {

        $customer->save();
        $this->customer = $customer->toArray();
    }

    public function setBilling(PagarMe\Billing $billing) {$this->billing = $billing->toArray();}
    public function setShipping(PagarMe\Shipping $shipping) {$this->shipping = $shipping->toArray();}

    public function useCreditCard(string $name, string $number, string $exp, string $cvv): void {

        $this->payment_method = 'credit_card';
        $card_id = $this->createCreditCard($name, $number, $exp, $cvv);
        $this->transaction['card_id'] = $card_id;
    }

    public function useBoleto(string $name, string $number, string $exp, string $cvv): void {

        $this->payment_method = 'boleto';
    }

    function createCreditCard(string $name, string $number, string $exp, string $cvv): string {

        return $this->client->cards()->create([
            'holder_name' => $name,
            'number' => $number,
            'expiration_date' => $exp,
            'cvv' => $cvv,
        ])->id;
    }
}