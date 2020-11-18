<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarouselTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carousel', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('position');
            $table->integer('page_id')->unsigned()->nullable()->index('page_id');
            $table->integer('blog_post_id')->unsigned()->nullable()->index('blog_post_id');
            $table->string('link', 1000)->nullable();
            $table->string('target', 20)->default('');
            $table->boolean('published')->default(0);
            $table->string('background_slide', 7)->nullable();
            $table->string('background_btn', 7)->nullable();
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
        Schema::drop('carousel');
    }
}
