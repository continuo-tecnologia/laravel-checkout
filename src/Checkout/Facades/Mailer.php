<?php

namespace MatheusFS\Laravel\Checkout\Facades;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Mailer {

    public static function copies(){
        
        $default_from = env('MAIL_FROM_ADDRESS', 'example@domain.com');
        $default_to = env('MAIL_TO_ADDRESS', $default_from);
        return config('checkout.copies', [ $default_to ]);
    }

    /**
     * Send transaction status update for customer entity
     * 
     * @param string $email
     * @param \MatheusFS\Laravel\Checkout\Mail\Postback\Customer $mailable
     */
    public static function mailCustomer($email, $mailable) {

        $recipients = array_merge($email, self::copies());

        Mail::to($recipients)->send($mailable);
        Log::info("Sent mail to $email and copies to " . implode(', ', self::copies()) . '.');
    }

    /**
     * Send transaction status update for supplier entity
     * 
     * @param string $email
     * @param \MatheusFS\Laravel\Checkout\Mail\Postback\Supplier $mailable
     */
    public static function mailSupplier($email, $mailable){
        
        $recipients = array_merge($email, self::copies());

        Mail::to($recipients)->send($mailable);
        Log::info("Sent mail to $email");
    }
}