<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediasCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id')->unsigned()->nullable()->index('parent_id');
            $table->string('slug', 32)->nullable()->index('slug');
            $table->integer('admin_id')->unsigned()->index('admin_id');
            $table->integer('position')->nullable()->default(0);
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
        Schema::drop('medias_categories');
    }
}
