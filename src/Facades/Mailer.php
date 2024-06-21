<?php

namespace MatheusFS\Laravel\Checkout\Facades;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use MatheusFS\Laravel\Checkout\Mail\Postback\Customer as PostbackToCustomer;
use MatheusFS\Laravel\Checkout\Mail\Postback\Development as PostbackToDevelopment;
use MatheusFS\Laravel\Checkout\Mail\Postback\Supplier as PostbackToSupplier;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;
use MatheusFS\Laravel\Checkout\Models\Product;
use MatheusFS\Laravel\Checkout\Shipping\Carriers\Correios\Api;

class Mailer {

    public static function sendMails($normalized){

        $customer_email = $normalized['customer']['email'];
        $customer_mailable = self::getCustomerMailable($normalized);
        self::mailCustomer($customer_email, $customer_mailable);

        if($normalized['status'] == 'paid'){

            self::mailSuppliers($normalized);
        }
    }

    /**
     * Send transaction status update for customer entity
     * 
     * @param string $email
     * @param \MatheusFS\Laravel\Checkout\Mail\Postback\Customer $mailable
     */
    public static function mailCustomer($email, $mailable) {

        $recipients = array_merge([$email], self::copies());

        Mail::to($recipients)->send($mailable);
        Log::info("Sent mail to $email and copies to " . implode(', ', self::copies()) . '.');
    }

    public static function mailSuppliers($normalized){

        $suppliers = [];

        foreach($normalized['items'] as $item){

            $id = $item['id'];

            try{ $product = Product::find($id); }catch(\Exception $exception){}

            if(isset($product) && $product instanceof Product){

                $supplier_id = $product->supplier->getKey();
                $suppliers[$supplier_id][] = $item;
                continue;
            }

            $exploded = explode(':', $id);
            $class = $exploded[0];
            $key = $exploded[1];

            if(class_exists($class) && $model = $class::find($key)){

                if($supplier = $model->supplier){

                    $supplier_id = $supplier->getKey();
                    $suppliers[$supplier_id][] = $item;
                }
            }
        }

        Log::debug('Suppliers in transaction', $suppliers);

        foreach($suppliers as $supplier_id => $items){

            $supplier = config('checkout.supplier.model')::find($supplier_id);

            $normalized['supplier'] = [
                'email' => $supplier->{config('checkout.supplier.property_mapping.email')},
                'name' => $supplier->{config('checkout.supplier.property_mapping.name')},
                'logo' => $supplier->{config('checkout.supplier.property_mapping.logo')}
            ];

            $normalized['items'] = $items;
            $normalized['shipping'] = $normalized['billing'];

            $customer_zipcode = $normalized['shipping']['address']['zipcode'];
            $normalized['shipping']['days_to_deliver'] = (new Api)->getFreight($supplier->zipcode, $customer_zipcode)[1]['deadline'];

            $supplier_mailable = self::getSupplierMailable($normalized);
            self::mailSupplier($normalized['supplier']['email'], $supplier_mailable);
        }
    }

    /**
     * Send transaction status update for supplier entity
     * 
     * @param string $email
     * @param \MatheusFS\Laravel\Checkout\Mail\Postback\Supplier $mailable
     */
    public static function mailSupplier($email, $mailable){

        $recipients = array_merge([$email], self::copies());

        Mail::to($recipients)->send($mailable);
        Log::info("Sent mail to $email");
    }

    public static function getCustomerMailable($normalized){

        $status = $normalized['status'];

        $status = [
            'id' => $status,
            'subject' => Status::subject($status),
            'alias' => Status::as($status),
            'instruction' => Status::instruction($status)
        ];

        return new PostbackToCustomer(
            $normalized['customer'],
            $normalized['shipping'],
            $normalized['items'],
            $status,
            $normalized['payment_method'],
            $normalized['boleto']
        );
    }

    public static function getSupplierMailable($normalized){

        $status = $normalized['status'];

        $status = [
            'id' => $status,
            'subject' => Status::subject($status),
            'alias' => Status::as($status),
            'instruction' => Status::instruction($status)
        ];

        return new PostbackToSupplier(
            $normalized['supplier']['name'],
            $normalized['supplier']['logo'],
            $normalized['customer'],
            $normalized['shipping'],
            $normalized['items'],
            $status,
            $normalized['payment_method']
        );
    }

    public static function copies(){

        $default_from = env('MAIL_FROM_ADDRESS', 'example@domain.com');
        $default_to = env('MAIL_TO_ADDRESS', $default_from);
        return config('checkout.copies', [ $default_to ]);
    }
}