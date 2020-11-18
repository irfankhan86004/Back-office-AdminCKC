<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTagsBlogCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tags_blog_categories', function(Blueprint $table)
		{
			$table->foreign('tag_id', 'tags_blog_categories_ibfk_1')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('blog_category_id', 'tags_blog_categories_ibfk_2')->references('id')->on('blog_posts_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tags_blog_categories', function(Blueprint $table)
		{
			$table->dropForeign('tags_blog_categories_ibfk_1');
			$table->dropForeign('tags_blog_categories_ibfk_2');
		});
	}

}
