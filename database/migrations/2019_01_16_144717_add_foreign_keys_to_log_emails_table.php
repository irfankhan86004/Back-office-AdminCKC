<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLogEmailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_emails', function (Blueprint $table) {
            $table->foreign('language_id', 'log_emails_ibfk_1')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_emails', function (Blueprint $table) {
            $table->dropForeign('log_emails_ibfk_1');
        });
    }
}
