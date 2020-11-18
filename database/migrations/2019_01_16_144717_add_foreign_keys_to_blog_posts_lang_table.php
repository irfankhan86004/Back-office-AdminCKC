<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBlogPostsLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts_lang', function (Blueprint $table) {
            $table->foreign('blog_post_id', 'blog_posts_lang_ibfk_1')->references('id')->on('blog_posts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('language_id', 'blog_posts_lang_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts_lang', function (Blueprint $table) {
            $table->dropForeign('blog_posts_lang_ibfk_1');
            $table->dropForeign('blog_posts_lang_ibfk_2');
        });
    }
}
