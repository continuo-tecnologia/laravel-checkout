<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use MatheusFS\LaravelCheckout\Checkout;

class PaymentLink {

    protected $checkout;

    protected $items;
    protected $max_orders;
    protected $expires_in;
    protected $review_informations;

    protected $payment_config;
    protected $has_boleto = true;
    protected $has_credit_card = true;

    protected $customer_config;
    protected $postback_config;
    /**
     * Construct a payment link model
     * @param Checkout $checkout Checkout for which the link will be generated
     * @param array $config A key/value configuration array contaning 'max_orders', 'expires_in' and 'review_informations'
     * @return PaymentLink A payment link model  
     */
    public function __construct(Checkout $checkout, array $config) {

        $this->checkout = $checkout;

        $this->max_orders = $config['max_orders'];
        $this->expires_in = $config['expires_in'];
        $this->review_informations = $config['review_informations'];
    }

    public static function redirect() {

    }

    public function _create() {

        return Api::client()->paymentLinks()->create([
            'amount' => intval($this->checkout->amount * 100),
            'items' => $this->checkout->items,
            'payment_config' => $this->_formatPaymentConfig(),
            'max_orders' => 1,
            'expires_in' => 60,
            'customer_config' => $this->_formatCustomerConfig(),
            'postback_config' => $this->_formatPostbackConfig(),
            'review_informations' => false,
        ]);
    }

    public function _formatPaymentConfig() {

        return [
            'boleto' => [
                'enabled' => $this->has_boleto,
                'expires_in' => 20,
            ],
            'credit_card' => [
                'enabled' => $this->has_credit_card,
                'free_installments' => 4,
                'interest_rate' => 25,
                'max_installments' => $this->checkout->items[0]['max_installments'] ?? 12, # Don't support multiple items
            ],
            'default_payment_method' => 'boleto',
        ];
    }

    public function _formatCustomerConfig() {

        return [
            'customer' => $this->checkout->customer,
            'billing' => $this->checkout->billing,
            'shipping' => $this->checkout->shipping,
        ];
    }

    public function _formatPostbackConfig() {

        return [
            'orders' => 'https://enmvg7vuktqd.x.pipedream.net/orders', //route('checkout.pagarme.postback.orders'),
            'transactions' => 'https://enmvg7vuktqd.x.pipedream.net/transactions', //route('checkout.pagarme.postback.transactions')
        ];
    }
}