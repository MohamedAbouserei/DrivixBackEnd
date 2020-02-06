<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Location extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('location', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('car_id')->unsigned();
          $table->foreign('car_id')->references('id')->on('car')->onDelete('cascade');
          $table->text('adress')->nullable(false);
          $table->text('long')->nullable(false);
          $table->text('lat')->nullable(false);
          $table->text('image');
          $table->date('date');
          $table->text('name');
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
