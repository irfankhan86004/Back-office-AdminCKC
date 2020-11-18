<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTagsBlogPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tags_blog_posts', function(Blueprint $table)
		{
			$table->foreign('tag_id', 'tags_blog_posts_ibfk_1')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('blog_post_id', 'tags_blog_posts_ibfk_2')->references('id')->on('blog_posts')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tags_blog_posts', function(Blueprint $table)
		{
			$table->dropForeign('tags_blog_posts_ibfk_1');
			$table->dropForeign('tags_blog_posts_ibfk_2');
		});
	}

}
