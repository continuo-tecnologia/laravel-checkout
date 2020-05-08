<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use MatheusFS\LaravelCheckoutPagarMe\Mail\Postback as MailPostback;

class Postback {

    protected $request;

    public function validate() {

        $body = $this->request->getContent();
        $signature = $this->request->header('X-Hub-Signature');

        return Api::client()->postbacks()->validate($body, $signature);
    }

    public function orders(Request $request) {

        $this->_log($request->all());
    }

    public function transactions(Request $request) {

        $this->request = $request;

        if($this->validate()){

            Mail::to($request->transaction['customer']['email'])->send(new MailPostback($request->all()));
            Mail::to('null@refresher.com.br' /* SUPPLIER E-MAIL */)->send(new MailPostback($request->all()));
            $this->_log($request->all());
        }

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
        $date_string = '[' . date('Y-m-d H:i:s') . ']';
        $content = $date_string . ' ' . json_encode($data);

        return !$disk->exists($file_path)
        ? $disk->put($file_path, $content)
        : $disk->prepend($file_path, $content);
    }
}