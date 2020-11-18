<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogPostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('published')->default(0);
            $table->boolean('featured')->default(0);
            $table->date('date');
            $table->string('heure', 8);
            $table->boolean('date_hide')->default(0);
            $table->string('written_by', 100)->nullable();
            $table->integer('admin_id')->unsigned()->nullable()->index('blog_posts_ibfk_1');
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
        Schema::drop('blog_posts');
    }
}
