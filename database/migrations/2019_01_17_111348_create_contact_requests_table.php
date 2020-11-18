<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactRequestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('lu')->nullable()->default(0);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('message')->nullable();
            $table->text('note', 65535)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contact_requests');
    }
}
