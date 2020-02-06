<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rolelocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('rolelocation', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('role_id')->unsigned();
          $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
          $table->text('location');
          $table->text('long');
          $table->text('lat');
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
