<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use MatheusFS\LaravelCheckoutPagarMe\Mail\Postback as MailPostback;

class Postback {

    public function orders(Request $request) {

        $this->_log($request->all());
    }

    public function transactions(Request $request) {

        Mail::to($request->customer['email'])->send(new MailPostback($request->all()));
        Mail::to('null@refresher.com.br'/* SUPPLIER E-MAIL */)->send(new MailPostback($request->all()));
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

        $file_path = '/matheusfs/laravel-checkout-pagarme/postback.log';
        $disk = Storage::disk('storage_logs');
        $date_string = '['.date('Y-m-d H:i:s').']';
        $content = $date_string.' '.json_encode($data);

        return !$disk->exists($file_path)
        ? $disk->put($file_path, $content)
        : $disk->prepend($file_path, $content);
    }
}