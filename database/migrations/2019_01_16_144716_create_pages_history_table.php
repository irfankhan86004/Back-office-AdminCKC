<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesHistoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned()->index('page_id');
            $table->integer('language_id')->unsigned()->index('language_id');
            $table->text('before', 65535)->nullable();
            $table->text('after', 65535)->nullable();
            $table->integer('admin_id')->unsigned()->index('admin_id');
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
        Schema::drop('pages_history');
    }
}
