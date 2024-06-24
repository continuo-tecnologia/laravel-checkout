<?php

namespace Tests\Unit;

use DateTime;
use Illuminate\Foundation\Testing\WithFaker;
use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Entities\Address as AddressEntity;
use MatheusFS\Laravel\Checkout\Entities\Item as ItemEntity;
use MatheusFS\Laravel\Checkout\Entities\Person;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Address;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Billing;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Customer;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Item;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Shipping;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Transaction;
use Tests\TestCase;

class CheckoutTest extends TestCase{

    use WithFaker;

    function setUp(): void{

        parent::setUp();

        Checkout::fake();
    }

    protected function tearDown(): void{

        Checkout::unfake();
    }

    public function test_payment_status_should_be_needs_payment(){

        # Act
        $status = Checkout::payment_status('new_product');

        # Assert
        $this->assertEquals('needs_payment', $status);
    }

    public function test_payment_status_should_be_paid(){

        # Arrange
        $this->store_paid_transaction('new_product', 'boleto');

        # Act
        $status = Checkout::payment_status('new_product');

        # Assert
        $this->assertEquals('paid', $status);
    }

    public function test_payment_status_should_be_requested_boleto_payment(){

        # Arrange
        $this->store_waiting_transaction('new_product', 'boleto');

        # Act
        $status = Checkout::payment_status('new_product');

        # Assert
        $this->assertEquals('requested_boleto_payment', $status);
    }

    public function test_payment_status_should_be_requested_cc_payment(){

        # Arrange
        $this->store_waiting_transaction('new_product', 'credit_card');

        # Act
        $status = Checkout::payment_status('new_product');

        # Assert
        $this->assertEquals('requested_cc_payment', $status);
    }

    public function test_payment_status_should_be_requested_pix_payment(){

        # Arrange
        $this->store_waiting_transaction('new_product', 'pix');

        # Act
        $status = Checkout::payment_status('new_product');

        # Assert
        $this->assertEquals('requested_pix_payment', $status);
    }

    // public function test_payment_status_should_be_requested_payment(){

    //     # Arrange
    //     $this->store_authorized_transaction('new_product', 'boleto');

    //     # Act
    //     $status = Checkout::payment_status('new_product');

    //     # Assert
    //     $this->assertEquals('requested_payment', $status);
    // }

    protected function store_paid_transaction($item_key, $payment_method){

        $this->store_transaction($item_key, $payment_method, 'paid');
    }

    protected function store_authorized_transaction($item_key, $payment_method){

        $this->store_transaction($item_key, $payment_method, 'authorized');
    }

    protected function store_waiting_transaction($item_key, $payment_method){

        $this->store_transaction($item_key, $payment_method, 'waiting_payment');
    }

    protected function store_transaction($item_key, $payment_method, $status){

        $person = new Person(
            $this->faker->name,
            $this->faker->email,
            '123.456.789-00',
            $this->faker->phoneNumber,
        );

        $address = new Address(
            new AddressEntity('95700-118', 22, 'Apto. 502')
        );

        $item = new ItemEntity($item_key, 'Novo produto', 10, 1, false);

        $transaction = new Transaction(
            $payment_method,
            new Customer($person),
            new Billing($address),
            new Shipping($address, 0, new DateTime),
            [new Item($item)]
        );

        $transaction->fake();
        $transaction->createTransaction($status);
    }
}