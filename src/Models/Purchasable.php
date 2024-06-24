<?php

namespace MatheusFS\Laravel\Checkout\Models;

use App\Models\SuperModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Log;
use MatheusFS\Laravel\Checkout\Events\PaymentConfirmed;

class Purchasable extends Model{

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $table = 'purchasables';

    public $purchasable_base = [
        'label',
        'description',
        'price',
        'image',
        'privacy',
        'max_installments',
        'presentational_video',
        'more_info',
        'external_link_url',
        'external_link_label',
        'boleto_url',
        'qr_code',
        'payment_status',
        'external_key',
    ];

    public $purchasable_physical = [
        'free_freight',
        'width',
        'length',
        'height',
        'weight',
    ];

    function purchasable(){ return $this->morphTo(); }

    function base_attributes(){

        foreach($this->purchasable_base as $attribute){

            $value = $this->{$attribute} ?? $this->purchasable->{$attribute};

            $attributes[$attribute] = $value;
        }

        return $attributes ?? [];
    }

    function getExternalKeyAttribute(){

        $purchasable = $this->purchasable;

        $class = get_class($purchasable);
        $key = $purchasable->key;

        return "$class:$key";
    }

    function confirm_payment(User $user){

        $message = "Found item payment for unpermitted user. Dispatching PaymentConfirmed...";

        $user_id = $user->getKey();
        $external_key = $this->external_key;

        Log::info($message, compact('user_id', 'external_key'));

        $order = [
            'customer' => [
                'external_id' => $user->key,
            ],
            'items' => [
                ['id' => $external_key],
            ]
        ];

        PaymentConfirmed::dispatch($order);
    }
}