<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Winchdriver extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('winchdriver', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('role_id')->unsigned();
          $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
        $table->integer('winch_company')->unsigned();
        $table->foreign('winch_company')->references('id')->on('winchcompany')->onDelete('cascade');
          $table->text('availability');
          $table->text('price_per_km');
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
