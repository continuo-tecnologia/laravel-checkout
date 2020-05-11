<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use Illuminate\Http\Request;
use MatheusFS\LaravelCheckout\Facades\Logger;
use MatheusFS\LaravelCheckout\Facades\Mailer;

class Postback {

    public function orders(Request $request) {

        Postback::validate($request);
    }

    public function transactions(Request $request) {

        Postback::validate($request);
        Mailer::sendMailsToInvolved($request);

        return response()->json([
            'message' => 'Postback transaction received correctly!',
            'transaction_id' => $request->id
        ]);
    }

    public static function validate($request) {

        $body = $request->getContent();
        $signature = $request->header('X-Hub-Signature');

        $is_valid = Api::client()->postbacks()->validate($body, $signature);
        
        $type = $is_valid ? 'valid' : 'invalid';

        $message = $is_valid 
        ? "Validated id: $request->id" 
        : "Fail validation for id: $request->id";

        $caller_method = debug_backtrace()[1]['function'];

        Logger::log($type, $message, $caller_method);

        return $is_valid ? true : abort(403, $message);
    }
}