<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarouselTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carousel', function (Blueprint $table) {
            $table->foreign('page_id', 'carousel_ibfk_1')->references('id')->on('pages')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->foreign('blog_post_id', 'carousel_ibfk_2')->references('id')->on('blog_posts')->onUpdate('SET NULL')->onDelete('SET NULL');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carousel', function (Blueprint $table) {
            $table->dropForeign('carousel_ibfk_1');
            $table->dropForeign('carousel_ibfk_2');
        });
    }
}
