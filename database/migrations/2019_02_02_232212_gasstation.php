<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Gasstation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        Schema::create('gasstation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->boolean('tier_repare');
            $table->boolean('blowing_air');
            $table->boolean('petrol_80');
            $table->boolean('petrol_92');
            $table->boolean('petrol_95');
            $table->boolean('align_wheel');
            $table->boolean('sollar');
            $table->boolean('gas');
            $table->boolean('car_washing');
            $table->boolean('blowing_nitro');
            $table->boolean('fix_suspension');
            $table->boolean('oil_change');
            $table->string('lat')->nullable(false);;
            $table->string('long')->nullable(false);;
            $table->string('address')->nullable(false);
            $table->timestamps();
        });
            /*
        */ 
        
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
