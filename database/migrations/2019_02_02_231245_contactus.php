<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contactus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('contactus', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('SP_ID')->unsigned();
          $table->foreign('SP_ID')->references('id')->on('serviceprovider')->onDelete('cascade');
          $table->text('message');
          $table->text('subject');
          $table->date('date');
          $table->text('status');
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
