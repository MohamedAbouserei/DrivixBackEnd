<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Workshoptype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('workshoptype', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workshop_id');
            $table->string('workshoptype')->nullable(false);
            $table->timestamps();
            $table->foreign('workshop_id')->references('id')->on('workshop')->onDelete('cascade');
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
