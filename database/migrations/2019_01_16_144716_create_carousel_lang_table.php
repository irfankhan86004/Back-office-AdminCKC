<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarouselLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carousel_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carousel_id')->unsigned()->index('carousel_id');
            $table->integer('language_id')->unsigned()->index('language_id');
            $table->string('title', 100)->nullable();
            $table->string('subtitle', 100)->nullable();
            $table->text('description', 65535)->nullable();
            $table->string('btn', 50)->nullable();
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
        Schema::drop('carousel_lang');
    }
}
