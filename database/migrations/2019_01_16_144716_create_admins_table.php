<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id')->unsigned()->nullable()->index('media_id');
            $table->string('medias_status', 100)->nullable();
            $table->string('email');
            $table->string('last_name')->default('');
            $table->string('first_name');
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
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
        Schema::drop('admins');
    }
}
