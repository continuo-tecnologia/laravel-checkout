<?php

namespace MatheusFS\Laravel\Checkout\Exceptions;

use Exception;
use Illuminate\Support\Facades\Input;
use MatheusFS\Laravel\Checkout\Facades\Logger;

class FormExeption extends Exception {

    public function report(){
        
        Logger::log('failed', $this->getMessage());
    }

    public function render($request){
        
        return back()->withError($this->getMessage())->withInput(Input::all());
    }
}
