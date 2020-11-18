<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMediasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->foreign('admin_id', 'medias_ibfk_1')->references('id')->on('admins')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->dropForeign('medias_ibfk_1');
        });
    }
}
