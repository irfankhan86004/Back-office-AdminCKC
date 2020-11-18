<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMediasCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medias_categories', function (Blueprint $table) {
            $table->foreign('parent_id', 'medias_categories_ibfk_1')->references('id')->on('medias_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('admin_id', 'medias_categories_ibfk_2')->references('id')->on('admins')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medias_categories', function (Blueprint $table) {
            $table->dropForeign('medias_categories_ibfk_1');
            $table->dropForeign('medias_categories_ibfk_2');
        });
    }
}
