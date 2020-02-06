<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('winch_driver_id');
            $table->increments('id');
            $table->string('time')->nullable(false);
            $table->string('user_location')->nullable(false);
            $table->string('user_long')->nullable(false);
            $table->string('user_lat')->nullable(false);
            $table->string('status')->nullable(false);
            $table->string('winch_location')->nullable(false);
            $table->string('winch_long')->nullable(false);
            $table->string('winch_lat')->nullable(false);
            $table->timestamps();
            $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('winch_driver_id')->references('id')->on('winchdriver')->onDelete('cascade');
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
