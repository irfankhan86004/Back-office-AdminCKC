<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->index('user_id');
            $table->integer('role_id')->unsigned()->index('role_id');
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
        Schema::drop('admin_role');
    }
}
