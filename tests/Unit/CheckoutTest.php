<?php

namespace MatheusFS\Laravel\Checkout\Tests\Unit;

use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Tests\TestCase;

class CheckoutTest extends TestCase{

    public function test_boleto_payment_should_return_correct_status(){

        # Arrange
        $expected_status = 'requested_boleto_payment';

        # Act
        $status = Checkout::payment_status('course_1');

        # Assert
        $this->assertEquals($expected_status, $status);
    }
}