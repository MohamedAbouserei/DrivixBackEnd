<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Estimate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('estimate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date')->nullable(false);
            $table->bigInteger('stars')->nullable(false);
            $table->unsignedInteger('users_id');
            $table->unsignedInteger('car_service_id');
            $table->timestamps();
            $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
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
