<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('name')->nullable(false);
            $table->string('description')->nullable(false);
            $table->string('brand')->nullable(false);
            $table->biginteger('price')->nullable(false);
            $table->unsignedInteger('shop_id');
            $table->string('status')->nullable(false);
            $table->timestamps();            
            $table->foreign('shop_id')->references('id')->on('sparesshop')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
