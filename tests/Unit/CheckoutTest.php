<?php

namespace Tests\Unit;

use MatheusFS\Laravel\Checkout\Checkout;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase{

    public function setUp(): void{

        parent::setUp();
    }

    public function test_boleto_payment_should_return_correct_status(){

        # Arrange
        $expected_status = 'requested_boleto_payment';

        # Act
        Checkout::payment_status('course_1');

        # Assert
        //
    }
}