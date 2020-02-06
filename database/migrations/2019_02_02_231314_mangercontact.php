<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mangercontact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('mangercontact', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('admin_id')->unsigned();
          $table->foreign('admin_id')->references('id')->on('admin')->onDelete('cascade');
          $table->integer('supervisor_id')->unsigned();
          $table->foreign('supervisor_id')->references('id')->on('supervisor')->onDelete('cascade');
          $table->text('message');
          $table->text('subject');
          $table->date('date');
          $table->text('status');
          $table->text('from_or_to');
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
