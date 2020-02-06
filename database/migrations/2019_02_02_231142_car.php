<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Car extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('car', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('User_id')->unsigned();
          $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
          $table->text('year');
          $table->text('color');
          $table->text('model');
          $table->text('brand');
          $table->timestamps();
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
