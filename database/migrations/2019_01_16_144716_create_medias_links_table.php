<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediasLinksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id_1')->unsigned()->index('media_id_1');
            $table->integer('media_id_2')->unsigned()->index('media_id_2');
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
        Schema::drop('medias_links');
    }
}
