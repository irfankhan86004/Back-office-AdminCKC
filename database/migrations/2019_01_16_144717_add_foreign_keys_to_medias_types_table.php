<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMediasTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medias_types', function (Blueprint $table) {
            $table->foreign('media_id', 'medias_types_ibfk_1')->references('id')->on('medias')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medias_types', function (Blueprint $table) {
            $table->dropForeign('medias_types_ibfk_1');
        });
    }
}
