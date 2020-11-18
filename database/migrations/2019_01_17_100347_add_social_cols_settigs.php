<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialColsSettigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('facebook')->nullable()->after('email');
            $table->string('twitter')->nullable()->after('facebook');
            $table->string('instagram')->nullable()->after('twitter');
            $table->string('linkedin')->nullable()->after('instagram');
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
                'facebook',
                'twitter',
                'instagram',
                'linkedin'
            ]);
        });
    }
}
