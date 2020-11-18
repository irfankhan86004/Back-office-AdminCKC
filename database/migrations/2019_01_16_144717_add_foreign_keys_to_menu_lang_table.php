<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMenuLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_lang', function (Blueprint $table) {
            $table->foreign('menu_id', 'menu_lang_ibfk_1')->references('id')->on('menu')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('language_id', 'menu_lang_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_lang', function (Blueprint $table) {
            $table->dropForeign('menu_lang_ibfk_1');
            $table->dropForeign('menu_lang_ibfk_2');
        });
    }
}
