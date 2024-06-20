<?php

namespace MatheusFS\Laravel\Checkout\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MatheusFS\Laravel\Checkout\Facades\Mailer;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Postback;

class PostbackController extends Controller{

    public function render_normalized(Request $request){

        $normalized = Postback::normalizeTransactionData($request);

        $mailable = Mailer::getCustomerMailable($normalized);

        return $mailable->render();
    }
}