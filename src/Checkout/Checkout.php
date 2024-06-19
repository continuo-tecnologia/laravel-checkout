<?php

/**
 * Checkout facade
 *
 * ...
 *
 * @copyright 2020 Natheus Ferreira da Silva
 * @license https://raw.githubusercontent.com/MatheusFS/laravel-checkout-pagarme/master/LICENSE MIT License
 * @version Release: @package_version@
 * @link https://packagist.org/packages/matheusfs/laravel-checkout-pagarme
 * @since Class available since Release 0.1
 */

namespace MatheusFS\Laravel\Checkout;

use Exception;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Log;
use MatheusFS\Laravel\Checkout\Entities\Item;
use MatheusFS\Laravel\Checkout\Facades\Logger;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Api;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\PaymentLink;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;
use MatheusFS\Laravel\Checkout\Support\Facades\Order;

class Checkout{

    protected $client;
    protected $payment_method;
    public $customer;
    public $billing;
    public $shipping;
    public $items = array();
    protected $transaction;
    public $amount = 0;

    /**
     * Initiate facade
     *
     * @param bool $sandbox Checkout in sandbox mode?
     */
    public function __construct($sandbox = false){

        $this->client = Api::client($sandbox);
    }

    static function payment_status($item_key){

        $last_transaction = Order::last($item_key);

        if(is_null($last_transaction)) return 'needs_payment';

        $last_status = $last_transaction->status;

        if($last_status === 'authorized'){

            (new Checkout)->confirm_order($last_transaction);

            return 'requested_payment';
        }

        if($last_status === 'paid') return 'paid';

        $is_processing = collect(Status::PROCESSING)->contains($last_status);
        $is_cancelled = collect(Status::CANCELLED)->contains($last_status);

        if($is_processing){

            $method = $last_transaction->payment_method;

            if($method === 'boleto') return 'requested_boleto_payment';
            if($method === 'pix') return 'requested_pix_payment';
            if($method === 'credit_card') return 'requested_cc_payment';
        }
        elseif($is_cancelled) return 'needs_payment';

        dd(compact('item_key', 'last_transaction', 'last_status'));
    }

    function confirm_order($transaction){

        $id = $transaction->id;
        $amount = $transaction->amount;

        Log::info('Checkout: Identified uncaptured transaction. Capturing...', compact('transaction'));

        try{

            $response = $this->client->transactions()->capture(compact('id', 'amount'));

            Log::info('Checkout: Captured transaction', compact('response'));
        }
        catch(Exception $exception){

            $this->invalidate_user_orders(request()->user(), 'authorized');
            $this->invalidate_user_orders(request()->user());

            Log::debug('Checkout: Error capturing transaction. Invalidated authorized user orders cache', compact('exception'));
        }
    }

    function pagarme_transactions($payload){

        return collect($this->client->transactions()->getList($payload));
    }

    function user_orders($status = null){

        $user = request()->user();

        $status_label = $status ?? 'all';

        $cache = cache();
        $cache_tags = [
            'checkout:orders',
            "checkout:orders:{$user->key}",
            "checkout:orders:$status_label",
            "checkout:orders:{$user->key}:$status_label",
        ];
        $cache_key = $cache_tags[3];

        $expires_at = now()->addMinutes(30);

        if($cache->getDefaultDriver() === 'redis'){

            $cache = $cache->tags($cache_tags);
        }

        return $cache->remember($cache_key, $expires_at, function() use ($user, $status){

            $payload_by_key = [ 'customer' => [ 'external_id' => $user->key ] ];
            $payload_by_email = [ 'customer' => [ 'external_id' => $user->email ] ];

            if($status){

                $payload_by_key['status'] = $status;
                $payload_by_email['status'] = $status;
            }

            $transactions_by_key = $this->pagarme_transactions($payload_by_key);
            $transactions_by_email = $this->pagarme_transactions($payload_by_email);

            $transactions = $transactions_by_key->concat($transactions_by_email)->all();

            return $transactions;
        });
    }

    function orders($status = null){

        $status_label = $status ?? 'all';

        $cache = cache();
        $cache_tags = [
            'checkout:orders',
            "checkout:orders:$status_label"
        ];
        $cache_key = $cache_tags[1];

        $expires_at = now()->addMinutes(30);
        $payload = compact('status');

        $get_status_list = fn() => $this->client->transactions()->getList($payload);

        if($cache->getDefaultDriver() === 'redis'){

            $cache = $cache->tags($cache_tags);
        }

        return $cache->remember($cache_key, $expires_at, $get_status_list);
    }

    public function redirectToPaymentLink(){

        $payment_link = new PaymentLink($this, [
            'max_orders' => 1,
            'expires_in' => 60,
            'review_informations' => false,
        ]);

        return $payment_link->redirect();
    }

    /**
     * Add item to checkout
     * 
     * @param Item $item
     */
    public function addItem(Item $item){

        array_push($this->items, $item);
    }

    /**
     * Set customer for checkout
     * 
     * @param \MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Customer $customer
     * @param boolean $save Save in Pagar.me
     */
    public function setCustomer($customer, $save = true){

        $customer->save();
        $this->customer = $customer;
    }

    /**
     * Set billing for checkout
     * 
     * @param \MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Billing $billing
     */
    public function setBilling($billing){$this->billing = $billing;}
    
        /**
         * Set shipping for checkout
         * 
         * @param \MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Shipping $shipping
         */
    public function setShipping($shipping){

        $this->shipping = $shipping->payload();
        $this->amount += $shipping->fee / 100;
    }

    public function useCreditCard(string $name, string $number, string $exp, string $cvv): void {

        $this->payment_method = 'credit_card';
        $card_id = $this->createCreditCard($name, $number, $exp, $cvv);
        $this->transaction['card_id'] = $card_id;
    }

    public function useBoleto(string $name, string $number, string $exp, string $cvv): void {

        $this->payment_method = 'boleto';
    }

    function createCreditCard(string $name, string $number, string $exp, string $cvv): string {

        return $this->client->cards()->create([
            'holder_name' => $name,
            'number' => $number,
            'expiration_date' => $exp,
            'cvv' => $cvv,
        ])->id;
    }

    static function invalidate_orders($status = null){

        if($user = request()->user()){

            self::invalidate_user_orders($user, $status);
        }
        else{

            self::invalidate_all_orders($status);
        }
    }

    static function invalidate_all_orders($status = null){

        if($status){

            $status_tag = self::status_tag($status);

            if(cache()->getDefaultDriver() === 'redis'){

                cache()->tags($status_tag)->flush();
            }
            else{

                cache()->forget($status_tag);
            }
        }
        else{

            $all_tag = 'checkout:orders';

            if(cache()->getDefaultDriver() === 'redis'){

                cache()->tags($all_tag)->flush();
            }
            else{

                cache()->forget("$all_tag:all");
                cache()->forget("$all_tag:paid");
                cache()->forget("$all_tag:authorized");
                cache()->forget("$all_tag:waiting_payment");
            }
        }
    }

    static function invalidate_user_orders(User $user, $status = null){

        if($status){

            $user_status_tag = self::user_status_tag($user, $status);

            if(cache()->getDefaultDriver() === 'redis'){

                cache()->tags($user_status_tag)->flush();
            }
            else{

                cache()->forget($user_status_tag);
            }
        }
        else{

            $user_tag = self::user_tag($user);

            if(cache()->getDefaultDriver() === 'redis'){

                cache()->tags($user_tag)->flush();
            }
            else{

                cache()->forget("$user_tag:all");
                cache()->forget("$user_tag:paid");
                cache()->forget("$user_tag:authorized");
                cache()->forget("$user_tag:waiting_payment");
            }
        }
    }

    static function user_tag(User $user){

        return "checkout:orders:{$user->key}";
    }

    static function status_tag($status = null){

        $status_label = $status ?? 'all';

        return "checkout:orders:$status_label";
    }

    static function user_status_tag(User $user, $status = null){

        $user_tag = self::user_tag($user);
        $status_label = $status ?? 'all';

        return "$user_tag:$status_label";
    }
}