<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBlogCategoriesLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_categories_lang', function (Blueprint $table) {
            $table->foreign('blog_category_id', 'blog_categories_lang_ibfk_1')->references('id')->on('blog_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('language_id', 'blog_categories_lang_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_categories_lang', function (Blueprint $table) {
            $table->dropForeign('blog_categories_lang_ibfk_1');
            $table->dropForeign('blog_categories_lang_ibfk_2');
        });
    }
}
