<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediasMediasCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias_medias_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id')->unsigned()->index('medias_medias_categories_ibfk_1');
            $table->integer('media_category_id')->unsigned()->index('medias_medias_categories_ibfk_2');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medias_medias_categories');
    }
}
