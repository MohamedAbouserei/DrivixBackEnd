<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Admin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('supervisor', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('User_id')->unsigned();
           $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
           $table->integer('admin_id')->unsigned();
           $table->foreign('admin_id')->references('id')->on('admin')->onDelete('cascade');
           $table->float('salary')->nullable(false);
           $table->integer('national_id')->unique()->nullable(false);;
           $table->integer('work_hours')->nullable(false);
           $table->date('hire_date')->nullable(false);
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
