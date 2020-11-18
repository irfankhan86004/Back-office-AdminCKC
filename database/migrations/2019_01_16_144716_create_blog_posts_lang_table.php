<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogPostsLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blog_post_id')->unsigned()->index('blog_post_id');
            $table->integer('language_id')->unsigned()->index('language_id');
            $table->string('name', 100)->default('');
            $table->string('url')->nullable()->index('blog_posts_lang_url_unique');
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->text('text', 65535)->nullable();
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
        Schema::drop('blog_posts_lang');
    }
}
