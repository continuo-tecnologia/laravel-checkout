<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use App\Facades\Cart as FacadesCart;
use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;
use MatheusFS\Laravel\Checkout\Facades\Cart;

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
        $this->name = "pl_" . $checkout->customer->email . "_" . intval(microtime(true) * 1000);
    }

    public function redirect() {

        $payment_link = $this->_create();
        return redirect($payment_link->url);
    }

    public function _create() {

        try {
            
            return Api::client()->paymentLinks()->create([
                // 'name' => $this->name,
                'amount' => FacadesCart::total() * 100,
                'items' => $this->items(),
                'payment_config' => $this->_formatPaymentConfig(),
                'max_orders' => 1,
                'expires_in' => 60,
                'customer_config' => $this->_formatCustomerConfig(),
                'postback_config' => $this->_formatPostbackConfig(),
                'review_informations' => false,
            ]);
        } catch (\PagarMe\Exceptions\PagarMeException $th) {

            report($th);
            throw new FormExeption("Ocorreu um erro ao criar o link de pagamento ({$th->getMessage()})");
        }
    }

    public function _formatPaymentConfig() {

        return [
            'boleto' => [
                'enabled' => $this->has_boleto,
                'expires_in' => 20
            ],
            'credit_card' => [
                'enabled' => $this->has_credit_card,
                'free_installments' => 4,
                'interest_rate' => 25,
                'max_installments' =>  12
            ],
            'default_payment_method' => 'boleto'
        ];
    }

    public function _formatCustomerConfig() {

        return [
            'customer' => $this->checkout->customer,
            'billing' => $this->checkout->billing,
            'shipping' => $this->checkout->shipping
        ];
    }

    public function _formatPostbackConfig() {

        return [
            'orders' => route('checkout.pagarme.postback.orders'),
            'transactions' => route('checkout.pagarme.postback.transactions')
        ];
    }

    public function items(){
        
        return collect($this->checkout->items)->map(function($item){

            return new Item($item);
        });
    }
}