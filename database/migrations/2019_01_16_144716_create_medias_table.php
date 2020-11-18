<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('original_name');
            $table->string('media_name')->nullable();
            $table->integer('size')->index();
            $table->string('md5')->nullable()->index('md5');
            $table->string('resolution', 32)->nullable();
            $table->string('extension', 10);
            $table->boolean('flip_x')->nullable();
            $table->boolean('flip_y')->nullable();
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->text('description', 65535)->nullable();
            $table->enum('type', array('file','picture','video'))->default('file');
            $table->integer('admin_id')->unsigned()->nullable()->index('medias_ibfk_1');
            $table->enum('status', array('draft','submitted','checked','published'));
            $table->boolean('public');
            $table->string('language', 2)->nullable()->index();
            $table->string('robots', 32)->nullable();
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
        Schema::drop('medias');
    }
}
