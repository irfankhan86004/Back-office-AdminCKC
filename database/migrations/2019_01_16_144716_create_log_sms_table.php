<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogSmsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('credit_left')->nullable();
            $table->string('slug', 100)->nullable();
            $table->string('to', 100)->nullable();
            $table->string('subject', 100)->nullable();
            $table->text('message', 65535)->nullable();
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
        Schema::drop('log_sms');
    }
}
