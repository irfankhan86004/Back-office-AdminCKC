<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAdminRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_role', function (Blueprint $table) {
            $table->foreign('admin_id', 'admin_role_ibfk_1')->references('id')->on('admins')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('role_id', 'admin_role_ibfk_2')->references('id')->on('roles')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_role', function (Blueprint $table) {
            $table->dropForeign('admin_role_ibfk_1');
            $table->dropForeign('admin_role_ibfk_2');
        });
    }
}
