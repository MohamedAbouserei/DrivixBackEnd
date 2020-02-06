<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Productimg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('productimg', function (Blueprint $table) {
            $table->increments('id');            
            $table->unsignedInteger('product_id');
            $table->string('date')->nullable(false);
            $table->string('image')->nullable(false);
            $table->string('status')->nullable(false);
            $table->string('type')->nullable(false);
            $table->timestamps();      
            $table->foreign("product_id")->references('id')->on('product')->onDelete('cascade');      
            
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
