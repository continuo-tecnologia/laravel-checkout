<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasablesTable extends Migration{

    const TABLE_NAME = 'purchasables';

    public function up(){

        Schema::create(self::TABLE_NAME, function(Blueprint $table){

            # Columns
            $table->bigIncrements('id');
            $table->morphs('purchasable');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->float('price')->nullable();
            $table->string('image')->nullable();
            $table->string('privacy')->nullable();
            $table->boolean('free_freight')->nullable();
            $table->float('width')->nullable();
            $table->float('length')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->integer('max_installments')->nullable();
            $table->string('presentational_video')->nullable();
            $table->text('more_info')->nullable();
            $table->string('external_link_url')->nullable();
            $table->string('external_link_label')->nullable();
            $table->json('metadata')->nullable();
        });
    }

    public function down(){

        Schema::dropIfExists(self::TABLE_NAME);
    }
}