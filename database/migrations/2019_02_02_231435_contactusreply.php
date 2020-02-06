<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contactusreply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('contactusreply', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('contact_id')->unsigned();
          $table->foreign('contact_id')->references('id')->on('contactus')->onDelete('cascade');
          $table->integer('supervisor_id')->unsigned();
          $table->foreign('supervisor_id')->references('id')->on('supervisor')->onDelete('cascade');
          $table->text('reply');
          $table->date('date');
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
