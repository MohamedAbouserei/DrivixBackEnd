<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Role extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('role', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('SP_ID')->unsigned();
          $table->foreign('SP_ID')->references('id')->on('serviceprovider')->onDelete('cascade');
          $table->integer('supervisor_id')->unsigned();
          $table->foreign('supervisor_id')->references('id')->on('supervisor')->onDelete('cascade');
          $table->text('type');
          $table->text('status');
          $table->float('work_from');
          $table->float('work_to');
          $table->text('name');
          $table->text('description');
          $table->boolean('lock');
          $table->text('workingdays');
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
