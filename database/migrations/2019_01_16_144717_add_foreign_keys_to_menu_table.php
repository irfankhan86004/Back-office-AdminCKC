<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMenuTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->foreign('page_id', 'menu_ibfk_1')->references('id')->on('pages')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->foreign('blog_post_id', 'menu_ibfk_2')->references('id')->on('blog_posts')->onUpdate('SET NULL')->onDelete('SET NULL');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropForeign('menu_ibfk_1');
            $table->dropForeign('menu_ibfk_2');
        });
    }
}
