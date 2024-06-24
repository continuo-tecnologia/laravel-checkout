<?php

namespace Tests\Api;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

use MatheusFS\Laravel\Checkout\Events\PaymentCancelled;
use MatheusFS\Laravel\Checkout\Events\PaymentConfirmed;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;
use MatheusFS\Laravel\Checkout\Mail\Postback\Customer as PostbackToCustomer;

use Tests\TestCase;

class PostbackTest extends TestCase{

    public function setUp(): void{

        parent::setUp();

        Event::fake();
        Mail::fake();
    }

    public function test_cancelled_postback_should_send_mail_dispatch_event_and_give_valid_response(){

        # Arrange
        $statuses = Status::CANCELLED;

        # Act
        Session::put('fake_validation', 'true');
        foreach($statuses as $status) $response = $this->send_postback($status);
        Session::forget('fake_validation');

        # Assert
        Mail::assertSent(PostbackToCustomer::class);
        Event::assertDispatchedTimes(PaymentCancelled::class, count($statuses));

        $response->assertOk();
        $response->assertJsonStructure(['error', 'message', 'transaction_id']);
    }

    public function test_paid_postback_should_send_mail_dispatch_event_and_give_valid_response(){

        # Act
        $response = $this->send_postback('paid');

        # Assert
        Mail::assertSent(PostbackToCustomer::class);
        Event::assertDispatched(PaymentConfirmed::class);

        $response->assertOk();
        $response->assertJsonStructure(['error', 'message', 'transaction_id']);
    }

    public function send_postback($status){

        $name = "postback/$status";

        $endpoint = '/checkout/pagarme/postback/transactions';

        $example = $this->get_example($name);

        $body = $example['payload'];

        return $this->post($endpoint, $body, $example['headers']);
    }
}