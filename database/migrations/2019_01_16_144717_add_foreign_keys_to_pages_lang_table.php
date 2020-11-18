<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPagesLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages_lang', function (Blueprint $table) {
            $table->foreign('page_id', 'pages_lang_ibfk_1')->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('language_id', 'pages_lang_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages_lang', function (Blueprint $table) {
            $table->dropForeign('pages_lang_ibfk_1');
            $table->dropForeign('pages_lang_ibfk_2');
        });
    }
}
