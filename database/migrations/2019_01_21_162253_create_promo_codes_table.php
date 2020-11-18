<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePromoCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promo_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100)->nullable();
			$table->string('code', 50)->nullable();
			$table->decimal('value', 10)->nullable();
			$table->enum('type', array('amount','percentage'))->nullable()->default('amount');
			$table->date('date_expire')->nullable();
			$table->integer('used')->default(0);
			$table->integer('max_use')->nullable();
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
		Schema::drop('promo_codes');
	}

}
