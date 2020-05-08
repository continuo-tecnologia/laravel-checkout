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

        $this->request = $request;

        if($this->validate()){

            $this->_log(__FUNCTION__, 'valid', "Validated postback orders id: $request->id");
        }else{

            $this->_log(__FUNCTION__, 'invalid', "Fail validation for orders id: $request->id");
        }
    }

    public function transactions(Request $request) {

        $this->request = $request;

        if($this->validate()){

            Mail::to($request->transaction['customer']['email'])->send(new MailPostback($request->all()));
            Mail::to('null@refresher.com.br' /* SUPPLIER E-MAIL */)->send(new MailPostback($request->all()));
            $this->_log(__FUNCTION__, 'valid', "Validated postback transaction id: $request->id");
        }else{

            $this->_log(__FUNCTION__, 'invalid', "Fail validation for transaction id: $request->id");
        }

    }

    protected function _log($model, $type, $message) {

        $this->_logInEmail("$model.".strtoupper($type).": $message");
        $this->_logInFile("$model.".strtoupper($type).": $message");
    }

    protected function _logInEmail($message) {

        Mail::raw($message, function ($message){
            $message->to('matheus@refresher.com.br');
            $message->to('marketplace@refresher.com.br');
        });
    }

    protected function _logInFile($message) {

        $file_path = '/matheusfs/laravel-checkout-pagarme/postback.log';
        $disk = Storage::disk('storage_logs');
        $date_string = '[' . date('Y-m-d H:i:s') . ']';
        $content = "$date_string $message ". PHP_EOL;

        return !$disk->exists($file_path)
        ? $disk->put($file_path, $content)
        : $disk->prepend($file_path, $content);
    }
}