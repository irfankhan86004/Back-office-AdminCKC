<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBlogPostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreign('admin_id', 'blog_posts_ibfk_1')->references('id')->on('admins')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign('blog_posts_ibfk_1');
        });
    }
}
