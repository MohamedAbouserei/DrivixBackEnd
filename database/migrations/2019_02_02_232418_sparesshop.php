<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sparesshop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sparesshop', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('car_service_id');
            $table->string('spareshoptype')->nullable(false);
            $table->timestamps();
            $table->foreign('car_service_id')->references('id')->on('carservice')->onDelete('cascade');
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
