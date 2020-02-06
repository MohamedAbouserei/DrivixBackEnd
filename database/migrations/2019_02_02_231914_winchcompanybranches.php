<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Winchcompanybranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('winchcompanybranches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('winch_company_branches')->unsigned();
            $table->foreign('winch_company_branches')->references('id')->on('winchcompany')->onDelete('cascade');
            $table->string('phone')->unique()->nullable(false);
            $table->string('address')->nullable(false);
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
