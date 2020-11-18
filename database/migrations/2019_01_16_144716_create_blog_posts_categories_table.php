<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogPostsCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blog_post_id')->unsigned()->index('blog_post_id');
            $table->integer('blog_category_id')->unsigned()->index('blog_category_id');
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
        Schema::drop('blog_posts_categories');
    }
}
