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

        $user_agent = $request->header('User-Agent');
        Logger::log('received', "From agent: $user_agent", __FUNCTION__);

        Postback::validate($request);
        Mailer::sendMailsToInvolved($request);

        Logger::log(
            'success', 
            "Succesfully processed transaction id: $request->id (Agent: $user_agent)", 
            __FUNCTION__
        );
        return response()->json([
            'error' => null,
            'message' => 'Postback transaction received correctly!',
            'transaction_id' => $request->id
        ]);
    }

    public static function validate($request) {

        $caller_method = debug_backtrace()[1]['function'];

        $body = $request->getContent();
        $signature = $request->header('X-Hub-Signature');
        $user_agent = $request->header('User-Agent');

        $is_valid = Api::client()->postbacks()->validate($body, $signature);
        
        $type = $is_valid ? 'valid' : 'invalid';

        $message = $is_valid 
        ? "Validated request for $caller_method id: $request->id" 
        : "Invalid request for $caller_method id: $request->id";

        Logger::log($type, "$message (Agent: $user_agent)", $caller_method);

        return $is_valid ? true : abort(403, $message);
    }
}