<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToBlogTagLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_tag_lang', function (Blueprint $table) {
            $table->foreign('id_tag', 'tag_lang_ibfk_1')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('id_blog', 'blog_posts_lang_ibfk_1')->references('id')->on('blog_posts')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_tag_lang', function (Blueprint $table) {
            $table->dropForeign('tag_lang_ibfk_1');
            $table->dropForeign('blog_posts_lang_ibfk_1');
        });
    }
}
