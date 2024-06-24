<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutTransactionsTable extends Migration{

    const TABLE_NAME = 'checkout_transactions';

    public function up(){

        Schema::create(self::TABLE_NAME, function(Blueprint $table){

            # Columns
            $table->bigIncrements('id');
            $table->json('data');
        });
    }

    public function down(){

        Schema::dropIfExists(self::TABLE_NAME);
    }
}