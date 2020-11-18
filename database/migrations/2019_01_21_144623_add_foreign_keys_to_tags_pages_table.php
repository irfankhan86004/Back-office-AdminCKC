<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTagsPagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tags_pages', function(Blueprint $table)
		{
			$table->foreign('tag_id', 'tags_pages_ibfk_1')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('page_id', 'tags_pages_ibfk_2')->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tags_pages', function(Blueprint $table)
		{
			$table->dropForeign('tags_pages_ibfk_1');
			$table->dropForeign('tags_pages_ibfk_2');
		});
	}

}
