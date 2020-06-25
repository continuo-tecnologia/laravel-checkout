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

namespace MatheusFS\Laravel\Checkout;

use MatheusFS\Laravel\Checkout\Entities\Item;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Api;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Billing;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Customer;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\PaymentLink;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Shipping;

class Checkout {

    protected $client;
    protected $payment_method;
    public $customer;
    public $billing;
    public $shipping;
    public $items = array();
    protected $transaction;
    public $amount = 0;

    /**
     * Initiate facade
     *
     * @param bool $sandbox Checkout in sandbox mode?
     */
    public function __construct($sandbox = false) {

        $this->client = Api::client($sandbox);
    }

    public function redirectToPaymentLink() {

        $payment_link = new PaymentLink($this, [
            'max_orders' => 1,
            'expires_in' => 60,
            'review_informations' => false,
        ]);

        return $payment_link->redirect();
    }

    /**
     * Add item to checkout
     * 
     * @param Item $item
     */
    public function addItem(Item $item) {

        array_push($this->items, $item);
    }

    /**
     * Set customer for checkout
     * 
     * @param \MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Customer $customer
     * @param boolean $save Save in Pagar.me
     */
    public function setCustomer($customer, $save = true) {

        $customer->save();
        $this->customer = $customer;
    }

    /**
     * Set billing for checkout
     * 
     * @param \MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Billing $billing
     */
    public function setBilling($billing) {$this->billing = $billing;}
    
        /**
         * Set shipping for checkout
         * 
         * @param \MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Shipping $shipping
         */
    public function setShipping($shipping) {

        $this->shipping = $shipping->payload();
        $this->amount += $shipping->fee / 100;
    }

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