<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSlugColumnIntoTypeInLogEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_emails', function (Blueprint $table) {
            $table->renameColumn('slug', 'type')->change();
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
            $table->renameColumn('type', 'slug')->change();
        });
    }
}
