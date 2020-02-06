<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Roleimgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('roleimgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date')->unique()->nullable(false);
            $table->string('image')->nullable(false);
            $table->string('type')->nullable(false);
            $table->string('status')->nullable(false);
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
