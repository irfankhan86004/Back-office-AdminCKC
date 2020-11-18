<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsBlogCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags_blog_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tag_id')->unsigned()->nullable()->index('tag_id');
			$table->integer('blog_category_id')->unsigned()->nullable()->index('blog_category_id');
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
		Schema::drop('tags_blog_categories');
	}

}
