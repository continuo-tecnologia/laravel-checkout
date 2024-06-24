<?php

/**
 * Checkout facade
 *
 * ...
 *
 * @copyright 2020 Natheus Ferreira da Silva
 * @license https://raw.githubusercontent.com/MatheusFS/laravel-checkout-pagarme/master/LICENSE MIT License
 * @version Release: @package_version@
 * @link https://packagist.org/packages/matheusfs/laravel-checkout
 * @since Class available since Release 0.1
 */

namespace MatheusFS\Laravel\Checkout;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Session;
use MatheusFS\Laravel\Checkout\Entities\Item;
use MatheusFS\Laravel\Checkout\Models\CreditCard;
use MatheusFS\Laravel\Checkout\Models\Transaction;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Api;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\PaymentLink;
use MatheusFS\Laravel\Checkout\Support\Facades\Order;
use MatheusFS\Laravel\Checkout\Support\Facades\Payment;

class Checkout{

    public $client;
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

        return Payment::status($item_key);
    }

    function confirm_order($transaction){

        Order::confirm($transaction);
    }

    function fake_transactions($payload){

        $customer = optional($payload)['customer'];
        $external_id = optional($customer)['external_id'];
        $status = $payload['status'];

        $where = [
            ['data', 'like', "%$external_id%"],
            ['data', 'like', "%$status%"],
        ];

        return Transaction::where($where)->pluck('data')->map(fn($data) => json_decode($data));
    }

    function pagarme_transactions($payload){

        if($this->is_fake()){

            return $this->fake_transactions($payload);
        }

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

        $expires_at = now()->addMinutes(10);

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

            $transactions = $transactions_by_key->concat($transactions_by_email);

            return $transactions->all();
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

        $expires_at = now()->addMinutes(10);
        $payload = compact('status');

        $get_status_list = $this->is_fake()
        ? fn() => $this->fake_transactions($payload)
        : fn() => $this->client->transactions()->getList($payload);

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

        $cc = new CreditCard($name, $number, $exp, $cvv);
        $cc->save();

        return $cc->getKey();
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

    static function pagarme_encryption_key(){

        return config('checkout.pagarme.encryption_key');
    }

    static function fake(){

        Session::put('fake_checkout', 'true');
    }

    static function unfake(){

        Session::forget('fake_checkout');
    }

    static function is_fake(){

        return Session::get('fake_checkout') === 'true';
    }
}