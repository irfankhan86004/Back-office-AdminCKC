<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned()->index('menu_id');
            $table->integer('language_id')->unsigned()->index('language_id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
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
        Schema::drop('menu_lang');
    }
}
