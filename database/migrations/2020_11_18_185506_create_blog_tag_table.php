<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_tag', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('id_blog')->nullable()->unsigned();;
			$table->integer('id_tag')->nullable()->unsigned();
            $table->timestamps();			
			
			$table->foreign('id_blog')->references('id')->on('tags');
			$table->foreign('id_tag')->references('id')->on('blog_posts');
        });
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_tag');
    }
}
