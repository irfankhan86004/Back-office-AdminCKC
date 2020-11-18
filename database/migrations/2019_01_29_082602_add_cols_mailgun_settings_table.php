<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsMailgunSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
	        $table->boolean('mailgun_use')->after('linkedin')->default(false)->nullable();
	        $table->string('mailgun_domain')->after('mailgun_use')->nullable();
	        $table->string('mailgun_endpoint')->after('mailgun_domain')->nullable();
	        $table->string('mailgun_secret')->after('mailgun_endpoint')->nullable();
	        $table->string('from_name')->after('mailgun_secret')->nullable();
	        $table->string('from_email')->after('from_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
	        $table->dropColumn([
		        'mailgun_use',
		        'mailgun_domain',
		        'mailgun_endpoint',
		        'mailgun_secret',
		        'from_name',
		        'from_email',
	        ]);
        });
    }
}
