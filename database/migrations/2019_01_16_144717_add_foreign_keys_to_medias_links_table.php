<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMediasLinksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medias_links', function (Blueprint $table) {
            $table->foreign('media_id_1', 'medias_links_ibfk_1')->references('id')->on('medias')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('media_id_2', 'medias_links_ibfk_2')->references('id')->on('medias')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medias_links', function (Blueprint $table) {
            $table->dropForeign('medias_links_ibfk_1');
            $table->dropForeign('medias_links_ibfk_2');
        });
    }
}
