<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use Illuminate\Http\Request;
use MatheusFS\LaravelCheckout\Facades\Logger;
use MatheusFS\LaravelCheckout\Facades\Mailer;

class Postback {

    public function orders(Request $request) {

        $this->_validate($request);
    }

    public function transactions(Request $request) {

        $this->_validate($request);
        Mailer::sendMailsToInvolved();
    }

    public function _validate($request) {

        $body = $this->request->getContent();
        $signature = $this->request->header('X-Hub-Signature');

        $is_valid = Api::client()->postbacks()->validate($body, $signature);
        $caller_method = debug_backtrace()[1]['function'];

        Logger::log(
            $caller_method, 
            $is_valid ? 'valid' : 'invalid', 
            $is_valid ? "Validated id: $request->id" : "Fail validation for id: $request->id"
        );
    }
}