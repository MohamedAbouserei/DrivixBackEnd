<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sitesettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('sitesettings', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('admin_id')->unsigned();
          $table->foreign('admin_id')->references('id')->on('admin')->onDelete('cascade');
          $table->text('setting_name');
          $table->text('slug');
          $table->text('value');
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
