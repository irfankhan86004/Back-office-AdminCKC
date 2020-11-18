<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesLangTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned()->index('page_id');
            $table->integer('language_id')->unsigned()->index('language_id');
            $table->string('url')->index('url');
            $table->string('canonical_url')->nullable();
            $table->string('name');
            $table->string('subname', 100)->nullable();
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->text('text', 65535)->nullable();
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
        Schema::drop('pages_lang');
    }
}
