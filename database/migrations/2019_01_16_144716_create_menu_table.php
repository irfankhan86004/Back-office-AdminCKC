<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->index('parent_id');
            $table->string('position', 10)->default('');
            $table->integer('page_id')->unsigned()->nullable()->index('page_id');
            $table->integer('blog_post_id')->unsigned()->nullable()->index('blog_post_id');
            $table->string('link')->nullable();
            $table->string('anchor')->nullable();
            $table->string('target', 20);
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
        Schema::drop('menu');
    }
}
