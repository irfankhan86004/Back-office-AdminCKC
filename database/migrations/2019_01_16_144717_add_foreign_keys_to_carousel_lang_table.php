<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarouselLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carousel_lang', function (Blueprint $table) {
            $table->foreign('carousel_id', 'carousel_lang_ibfk_1')->references('id')->on('carousel')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('language_id', 'carousel_lang_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carousel_lang', function (Blueprint $table) {
            $table->dropForeign('carousel_lang_ibfk_1');
            $table->dropForeign('carousel_lang_ibfk_2');
        });
    }
}
