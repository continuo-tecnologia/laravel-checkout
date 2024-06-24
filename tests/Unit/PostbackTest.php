<?php

namespace Tests\Unit;

use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Postback;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Tests\TestCase;

class PostbackTest extends TestCase{

    public function test_normalize_should_return_correct_keys(){

        # Arrange
        $request = $this->create_request_from_example('postback/paid');

        # Act
        $normalized = Postback::normalizeTransactionData($request);

        # Assert
        $required_keys = [
            'transaction_id',
            'status',
            'amount',
            'items',
            'boleto',
            'payment_method',
            'customer',
            'billing',
            'shipping',
        ];

        foreach($required_keys as $key) $this->assertArrayHasKey($key, $normalized);
    }

    public function test_validade_should_allow_valid_request(){

        # Arrange
        $request = $this->create_request_from_example('postback/paid');

        # Act
        $result = Postback::validate($request);

        # Assert
        $this->assertNull($result);
    }

    public function test_validade_should_reject_invalid_request(){

        # Arrange
        $request = $this->create_request_from_example('postback/refused');
        $this->expectException(HttpException::class);

        # Act
        Postback::validate($request);
    }
}