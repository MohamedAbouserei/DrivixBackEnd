<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Parking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('parking', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('location_id')->unsigned();
          $table->foreign('location_id')->references('car_id')->on('location')->onDelete('cascade');
          $table->float('cost_limit');
          $table->float('cost_hour');
          $table->float('hour_limit');
          $table->float('parking_time');
          $table->text('type');
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
