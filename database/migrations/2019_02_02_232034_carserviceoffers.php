<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Carserviceoffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('carserviceoffers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('carservice_id');
            $table->string('startdate')->nullable(false);
            $table->string('enddate')->nullable(false);
            $table->string('describtion')->nullable(false);
            $table->string('img')->nullable(false);
            $table->bigInteger('offernum')->nullable(false);
            $table->string('title')->nullable(false);
            $table->foreign('carservice_id')->references('id')->on('carservice')->onDelete('cascade');;

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
