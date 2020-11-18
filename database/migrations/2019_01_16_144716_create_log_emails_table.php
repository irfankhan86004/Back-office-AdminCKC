<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogEmailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id')->unsigned()->index('language_id');
            $table->string('slug', 100)->nullable();
            $table->string('from')->nullable()->default('');
            $table->string('to')->nullable()->default('');
            $table->string('subject')->nullable()->default('');
            $table->text('message', 65535);
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
        Schema::drop('log_emails');
    }
}
