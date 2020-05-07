<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use MatheusFS\LaravelCheckoutPagarMe\Mail\Postback as MailPostback;

class Postback {

    public function orders(Request $request) {

        $this->_log($request->all());
    }

    public function transactions(Request $request) {

        $this->_log($request->all());
    }

    protected function _log($data) {

        $this->_logInEmail($data);
        $this->_logInFile($data);
    }

    protected function _logInEmail($data) {

        Mail::to('matheus@refresher.com.br')->send(new MailPostback($data));
    }

    protected function _logInFile($data) {

        $file_path = storage_path().'/logs/matheusfs/laravel-checkout-pagarme/postback.log';

        return !File::exists($file_path)
        ? Storage::put($file_path, $data)
        : File::append($file_path, $data);
    }
}