<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediasTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id')->unsigned()->index('medias_types_ibfk_1');
            $table->integer('type_id')->unsigned();
            $table->string('type');
            $table->integer('position')->default(1);
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
        Schema::drop('medias_types');
    }
}
