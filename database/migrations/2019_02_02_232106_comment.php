<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date')->nullable(false);
            $table->unsignedInteger('carservice_id');
            $table->string('comment')->nullable(false);
            $table->unsignedInteger('followed_comment');
            $table->unsignedInteger('users_id');
            $table->timestamps();
            $table->foreign('carservice_id')->references('id')->on('carservice')->onDelete('cascade');
            $table->foreign('followed_comment')->references('id')->on('comment')->onDelete('cascade');
            $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade');
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
