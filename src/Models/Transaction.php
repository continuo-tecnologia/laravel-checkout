<?php

namespace MatheusFS\Laravel\Checkout\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model{

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $table = 'checkout_transactions';
}