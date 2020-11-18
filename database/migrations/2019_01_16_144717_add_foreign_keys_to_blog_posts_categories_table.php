<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBlogPostsCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts_categories', function (Blueprint $table) {
            $table->foreign('blog_post_id', 'blog_posts_categories_ibfk_1')->references('id')->on('blog_posts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('blog_category_id', 'blog_posts_categories_ibfk_2')->references('id')->on('blog_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts_categories', function (Blueprint $table) {
            $table->dropForeign('blog_posts_categories_ibfk_1');
            $table->dropForeign('blog_posts_categories_ibfk_2');
        });
    }
}
