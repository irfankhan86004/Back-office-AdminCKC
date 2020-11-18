<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColMailgunLogEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_emails', function (Blueprint $table) {
            $table->string('mailgun_id')->nullable()->after('message');
            $table->string('mailgun_status')->nullable()->after('mailgun_id');
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
            $table->dropColumn(['mailgun_id', 'mailgun_status']);
        });
    }
}
