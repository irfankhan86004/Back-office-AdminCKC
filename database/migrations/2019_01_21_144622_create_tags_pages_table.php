<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsPagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags_pages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tag_id')->unsigned()->nullable()->index('tag_id');
			$table->integer('page_id')->unsigned()->nullable()->index('page_id');
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
		Schema::drop('tags_pages');
	}

}
