<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GasstationSupervisormanage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('gasstation_supervisormanager', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gas_station_id');
            $table->unsignedInteger('supervisor_id');
            $table->string('date')->unique()->nullable(false);
            $table->string('type')->nullable(false);            
            $table->timestamps();
            $table->foreign('gas_station_id')->references('id')->on('gasstation')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('supervisor')->onDelete('cascade');
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
