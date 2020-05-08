<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

class Checkout {

    protected $client;
    protected $payment_method;
    protected $customer;
    protected $billing;
    protected $shipping;
    protected $items = array();
    protected $transaction;

    public function __construct(bool $sandbox = false) {

        $this->client = Api::client($sandbox);
    }

    public function getPaymentLink(float $amount, bool $boleto = true, bool $credit_card = true): string {

        return $this->client->paymentLinks()->create([
            'amount' => intval($amount) * 100,
            'items' => $this->items,
            'payment_config' => [
                'boleto' => [
                    'enabled' => $boleto,
                    'expires_in' => 20,
                ],
                'credit_card' => [
                    'enabled' => $credit_card,
                    'free_installments' => 4,
                    'interest_rate' => 25,
                    'max_installments' => $this->items[0]['max_installments'] ?? 12, # Don't support multiple items
                ],
                'default_payment_method' => 'boleto',
            ],
            'max_orders' => 1,
            'expires_in' => 60,
            'customer_config' => [
                'customer' => $this->customer,
                'billing' => $this->billing,
                'shipping' => $this->shipping,
            ],
            'postback_config' => [
                'orders' => 'https://enmvg7vuktqd.x.pipedream.net/', //route('checkout.pagarme.postback.orders'),
                'transactions' => 'https://enmvg7vuktqd.x.pipedream.net/' //route('checkout.pagarme.postback.transactions')
            ],
            'review_informations' => false,
        ])->url;
    }

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

    public function addItem(string $id, string $title, float $unit_price, int $quantity = 1, bool $tangible = true): void {
        array_push($this->items, [
            'id' => $id,
            'title' => $title,
            'unit_price' => $unit_price * 100,
            'quantity' => $quantity,
            'tangible' => $tangible,
        ]);
    }

    public function setCustomer(string $name, string $cpf, string $phone, string $email): void {

        $customer = new Customer($name, $cpf, $phone, $email);
        $customer->save();
        $this->customer = $customer->toArray();
    }

    public function setBilling($name, Address $address) {

        $this->billing = [
            'name' => $name,
            'address' => $address->toArray(),
        ];
    }

    public function setShipping(Shipping $shipping) {

        $this->shipping = $shipping->toArray();
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