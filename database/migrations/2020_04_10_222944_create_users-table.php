<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('username', 100)->unique();
            $table->string('password', 255);
            $table->boolean('active')->default(false);
            $table->string('api_token', 255)->nullable()->index('api_token_index');
            $table->dateTime('last_login')->nullable();
            $table->string('lang', 5)->default('en');
            $table->unsignedBigInteger('loggable_id');
            $table->string('loggable_type', 100);

//
//            $table->tinyInteger('usrtype', false, true)->index('type_index');
//            $table->foreignId('parent_id')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
