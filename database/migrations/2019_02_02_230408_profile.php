<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Profile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('profile', function (Blueprint $table) {
         $table->increments('id');
         $table->integer('User_id')->unsigned();
      $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
           $table->String('phone')->unique()->nullable(false);;
           $table->String('gender')->nullable(false);
           $table->date('DOB')->nullable(false);
           $table->text('image');
           $table->text('location');
           $table->text('job');

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
